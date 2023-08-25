<script>
  export default {
    props: ['file', 'format'],
    methods: {
      addFile() {
        this.file.push({
          file: null,
        });
      },
      removeFile(idx) {
        this.file.splice(idx, 1);
      },
      handleFileChange(idx) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
          const allowedFormats = this.format.split(',').map(format => format.trim());
          const fileExtension = selectedFile.name.split('.').pop().toLowerCase();

          console.log(allowedFormats);
            console.log(fileExtension);

            if (allowedFormats.includes(fileExtension)) {
                this.file[idx].file = selectedFile;
            } else {
                alert('Format file tidak sesuai');
            }
        }
      },
    },
  };
  </script>
  <template>
    <div>
      <div class="d-flex flex-row-reverse bd-highlight">
        <div class="p-2 bd-highlight">
          <button class="btn btn-outline-primary" @click="addFile">Tambah File</button>
        </div>
      </div>
      <div class="row my-1" v-for="(data, idx) in file" :key="idx">
        <div class="col-10">
          <input type="file" class="form-control" :accept="format" @change="handleFileChange(idx)">
        </div>
        <div class="col-2">
          <button class="btn btn-outline-danger" @click="removeFile(idx)">x</button>
        </div>
      </div>
    </div>
  </template>