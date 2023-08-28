<script>
  export default {
    props: ['file', 'format'],
    methods: {
      addFile() {
        this.file.push({
          file: null,
        });
      },
      removeFile(item) {
        const index = this.file.indexOf(item);
        if (index > -1) {
          this.file.splice(index, 1);
        }
      },
      handleFileChange(idx) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
          const allowedFormats = this.format ? this.format.split(',').map(format => format.trim()) : ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'mp4', 'mkv', 'avi', 'mp3', 'wav', 'zip', 'rar'];
          const fileExtension = selectedFile.name.split('.').pop().toLowerCase();

            if (allowedFormats.find(format => format === fileExtension)) {
                this.file[idx].file = selectedFile;
                this.file[idx].name = selectedFile.name;
            } else {
                alert('Format file tidak sesuai');
                this.file[idx] = null;
                this.$refs.file[idx].value = '';
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
          <input type="file" class="form-control" :accept="format" @change="handleFileChange(idx)" ref="file" />
        </div>
        <div class="col-2">
          <button class="btn btn-outline-danger" @click="removeFile(data)">x</button>
        </div>
      </div>
    </div>
  </template>