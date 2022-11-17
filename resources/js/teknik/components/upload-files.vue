<script>
export default {
  name: "VueUploadImages", // vue component name
  data() {
    return {
      error: "",
      files: [],
      dropped: 0,
      Imgs: [],
    };
  },
  props: {
    max: Number,
    uploadMsg: String,
    maxError: String,
    fileError: String,
    clearAll: String,
  },
  methods: {
    dragOver() {
      this.dropped = 2;
    },
    dragLeave() {},
    drop(e) {
      let status = true;
      let files = Array.from(e.dataTransfer.files)
      if (e && files) {
        files.forEach((file) => {
          if (file.type.startsWith("image") === false) status = false;
        });
        if (status == true) {
          if (
            this.$props.max &&
            files.length + this.files.length > this.$props.max
          ) {
            this.error = this.$props.maxError
              ? this.$props.maxError
              : `Maximum files is` + this.$props.max;
          } else {
            this.files.push(...files);
            this.previewImgs();
          }
        } else {
          this.error = this.$props.fileError
            ? this.$props.fileError
            : `Unsupported file type`;
        }
      }
      this.dropped = 0;
    },
    append() {
      this.$refs.uploadInput.click();
    },
    readAsDataURL(file) {
      return new Promise(function (resolve, reject) {
        let fr = new FileReader();
        fr.onload = function () {
          resolve(fr.result);
        };
        fr.onerror = function () {
          reject(fr);
        };
        fr.readAsDataURL(file);
      });
    },
    deleteImg(index) {
      this.Imgs.splice(index, 1);
      this.files.splice(index, 1);
      this.$emit("changed", this.files);
      this.$refs.uploadInput.value = null;
    },
    previewImgs(event) {
      if (
        this.$props.max &&
        event &&
        event.currentTarget.files.length + this.files.length > this.$props.max
      ) {
        this.error = this.$props.maxError
          ? this.$props.maxError
          : `Maximum files is` + this.$props.max;
        return;
      }
      if (this.dropped == 0) this.files.push(...event.currentTarget.files);
      this.error = "";
      this.$emit("changed", this.files);
      let readers = [];
      if (!this.files.length) return;
      for (let i = 0; i < this.files.length; i++) {
        readers.push(this.readAsDataURL(this.files[i]));
      }
      Promise.all(readers).then((values) => {
        this.Imgs = values;
      });
    },
    reset() {
      this.$refs.uploadInput.value = null;
      this.Imgs = [];
      this.files = [];
      this.$emit("changed", this.files);
    },
  },
};
</script>

<template>
  <div
    class="container"
    @dragover.prevent="dragOver"
    @dragleave.prevent="dragLeave"
    @drop.prevent="drop($event)"
  >
    <div class="drop" v-show="dropped == 2"></div>
    <!-- Error Message -->
    <div v-show="error" class="error">
      {{ error }}
    </div>

    <!-- To inform user how to upload image -->
    <div v-show="Imgs.length == 0" class="beforeUpload">
      <input
        type="file"
        style="z-index: 1"
        ref="uploadInput"
        @change="previewImgs"
        multiple
      />

<svg width="163" height="108" viewBox="0 0 163 108" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M131.15 44.019C129.082 44.019 127.036 44.2181 125.029 44.613C124.114 19.8567 103.777 0 78.9101 0C56.0422 0 36.693 16.9949 33.2985 39.3362C14.8613 39.7888 0 54.9951 0 73.621C0 92.5313 15.3165 107.916 34.1432 107.916H62.7082C64.8685 107.916 66.6189 106.157 66.6189 103.988C66.6189 101.818 64.8685 100.06 62.7082 100.06H34.1432C19.6292 100.06 7.82145 88.1994 7.82145 73.621C7.82145 59.0426 19.6297 47.1824 34.1432 47.1824C34.856 47.1824 35.61 47.2175 36.4474 47.2903L40.4103 47.6349L40.6861 43.6493C42.0737 23.5784 58.8637 7.85614 78.9101 7.85614C100.045 7.85614 117.24 25.1276 117.24 46.3565C117.24 47.3322 117.194 48.3755 117.099 49.545L116.586 55.8132L122.419 53.526C125.211 52.4309 128.149 51.8757 131.151 51.8757C144.4 51.8757 155.179 62.7025 155.179 76.0103C155.179 89.3176 144.4 100.144 131.151 100.144C130.803 100.144 120.405 100.123 110.05 100.102C99.7709 100.082 89.5337 100.061 89.1937 100.061C86.7597 100.061 85.4874 98.8832 85.4113 96.5615V54.1597L89.6552 59.2228C91.0458 60.883 93.5132 61.0957 95.1651 59.6978C96.8175 58.301 97.0292 55.8231 95.6381 54.1634L86.233 42.9412C85.0483 41.5276 83.3459 40.7168 81.5621 40.7168C79.7782 40.7168 78.0758 41.5276 76.8916 42.9412L67.4866 54.1634C66.0954 55.8231 66.3071 58.301 67.9595 59.6978C68.6932 60.3184 69.5869 60.6212 70.4759 60.6212C71.5902 60.6212 72.6962 60.1456 73.4695 59.2228L77.5893 54.3069V96.5997C77.5893 101.149 80.6798 107.916 89.1932 107.916C89.5331 107.916 99.7626 107.937 110.034 107.958C120.397 107.979 130.802 108 131.151 108C148.713 108 163 93.6494 163 76.0098C163 58.3696 148.712 44.019 131.15 44.019Z" fill="#000002"/>
</svg>
      <p class="mainMessage">
        {{ uploadMsg ? uploadMsg : "Click to upload or drop your files here" }}
      </p>
    </div>
    <div class="imgsPreview" v-show="Imgs.length > 0">
      <button type="button" class="clearButton" @click="reset">
        {{ clearAll ? clearAll : "clear All" }}
      </button>
      <div class="row">
        <div class="col-3" v-for="(img, i) in Imgs" :key="i">
                <div class="imageHolder">
        <span>{{ files[i].name }}</span>
        <span class="delete" style="color: white" @click="deleteImg(--i)">
          <svg
            class="icon"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </span>
        <div class="plus" @click="append" v-if="++i == Imgs.length">+</div>
      </div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
.container {
  width: 100%;
  height: 100%;
  background: #f7fafc;
  border: 0.5px solid #a3a8b1;
  border-radius: 10px;
  padding: 30px;
  position: relative;
}
.drop {
  width: 100%;
  height: 100%;
  top: 0;
  border-radius: 10px;
  position: absolute;
  background-color: #f4f6ff;
  left: 0;
  border: 3px dashed #a3a8b1;
}
.error {
  text-align: center;
  color: red;
  font-size: 15px;
}
.beforeUpload {
  position: relative;
  text-align: center;
}
.beforeUpload input {
  width: 100%;
  margin: auto;
  height: 100%;
  opacity: 0;
  position: absolute;
  background: red;
  display: block;
}
.beforeUpload input:hover {
  cursor: pointer;
}
.beforeUpload .icon {
  width: 150px;
  margin: auto;
  display: block;
}
.imgsPreview .imageHolder {
  width: 150px;
  height: 150px;
  background: #fff;
  position: relative;
  border-radius: 10px;
  margin: 5px 5px;
  display: inline-block;
}
.imgsPreview .imageHolder img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}
.imgsPreview .imageHolder .delete {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 29px;
  height: 29px;
  color: #fff;
  background: red;
  border-radius: 50%;
}
.imgsPreview .imageHolder .delete:hover {
  cursor: pointer;
}
.imgsPreview .imageHolder .delete .icon {
  width: 66%;
  height: 66%;
  display: block;
  margin: 4px auto;
}
.imgsPreview .imageHolder .plus {
  color: #2d3748;
  background: #f7fafc;
  border-radius: 50%;
  font-size: 21pt;
  height: 30px;
  width: 30px;
  text-align: center;
  border: 1px dashed;
  line-height: 23px;
  position: absolute;
  right: -42px;
  bottom: 43%;
}
.plus:hover {
  cursor: pointer;
}
.clearButton {
  color: #2d3748;
  position: absolute;
  top: 7px;
  right: 7px;
  background: none;
  border: none;
  cursor: pointer;
}
.mainMessage{
  margin-top: 20px;
}
</style>