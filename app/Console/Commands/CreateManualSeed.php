<?php

namespace ALttP\Console\Commands;

use ALttP\Jobs\SendPatchToDisk;
use ALttP\NamedSeed;
use ALttP\Rom;
use ALttP\Seed;
use ALttP\Support\Flips;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateManualSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alttp:createmanualseed {file} {branch=base}'
        . ' {--notes= : notes to display}'
        . ' {--name= : name to insert into named seeds table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a seed from an extant file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->argument('file');
        $branch = $this->argument('branch');
        $name = $this->option('name');

        if (!is_string($file)) {
            $this->error('argument not string');

            return 101;
        }

        $seed = new Seed;
        $seed->build = Rom::BUILD_INFO[$branch]['BUILD'];
        $seed->branch = $branch;
        $seed->logic = 0;
        $seed->game_mode = "manual";

        $spoiler = [
            'meta' => [
                'build' => $seed->build,
                'notes' => $this->option('notes'),
            ],
        ];

        $seed->spoiler = json_encode($spoiler);

        $tmp_file = tempnam(sys_get_temp_dir(), __CLASS__);
        if ($tmp_file === false) {
            throw new \Exception('Unable to create tmp file');
        }
        $base_rom_patch = public_path(sprintf('bps/%s.bps', Rom::BUILD_INFO[$branch]['HASH']));
        $base_rom_data = resolve(Flips::class)->applyBpsToFile(config('alttp.base_rom'), $base_rom_patch);
        file_put_contents($tmp_file, $base_rom_data);

        try {
            $seed->patch = $this->makeJsonPatch($tmp_file, $file);
            $seed->save();
            SendPatchToDisk::dispatch($seed);
            if ($name) {
                $named_seed = NamedSeed::firstOrNew(['name' => $name]);
                $named_seed->hash = $seed->hash;
                $named_seed->save();
            }
            $this->info(sprintf('seed created: %s', $seed->hash));
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 201;
        } finally {
            unlink($tmp_file);
        }
    }

    /**
     * Create a json patch for internal services.
     *
     * @param string  $base_rom  base patched ROM to base patch against
     * @param string  $new_rom  new ROM to make patch from
     *
     * @throws \Exception  if any filesystem issues arrise
     *
     * @return string
     */
    private function makeJsonPatch(string $base_rom, string $new_rom): string
    {
        $original_rom = fopen($base_rom, "r");

        if ($original_rom === false) {
            throw new Exception('Could not open base rom file');
        }

        $updated_rom = fopen($new_rom, "r");

        if ($updated_rom === false) {
            throw new Exception('Could not open updated ROM');
        }

        $i = 0;
        $out = [];
        while (!feof($original_rom)) {
            $original_byte = fread($original_rom, 1);
            $updated_byte = fread($updated_rom, 1);
            if ($updated_byte !== $original_byte && $updated_byte !== false) {
                $out[$i] = [unpack('C*', $updated_byte)[1]];
            }
            $i++;
        }
        fclose($updated_rom);
        fclose($original_rom);

        $backwards = array_reverse($out, true);
        foreach ($backwards as $off => $_) {
            if (isset($backwards[$off - 1])) {
                $backwards[$off - 1] = array_merge($backwards[$off - 1], $backwards[$off]);
                unset($backwards[$off]);
            }
        }
        $forwards = array_reverse($backwards, true);

        array_walk($forwards, function (&$write, $address) {
            $write = [$address => $write];
        });

        return json_encode(array_values($forwards));
    }
}
