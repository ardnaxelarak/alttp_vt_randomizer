import FileSaver from "file-saver";

export default class Multiworld {
  constructor(data) {
    this.worlds = data.worlds;
    this.multidata = data.multidata;
    this.spoiler = data.spoiler;
    this.generated = data.generated;
    this.hash = data.hash;
  }

  save(filename) {
    FileSaver.saveAs(new Blob([new Uint8Array(this.multidata)], { type: 'application/octet-stream' }), filename);
  }

  downloadFilename() {
    return this.hash;
  }
}
