@extends('layouts.default', ['title' => __('navigation.daily') . ' - '])

@section('content')
<h3>{{ __('daily.header') }}</h3>
<div class="card card-body bg-themed">
    @foreach (__('daily.content') as $block)
        <p>{!! $block !!}</p>
    @endforeach
</div>

<Hashloader branch="{{ $branch }}" current_rom_hash="{{ $md5 }}" override-base-bps="{{ $bpsLocation }}" hash="{{ $hash }}"></Hashloader>
@overwrite
