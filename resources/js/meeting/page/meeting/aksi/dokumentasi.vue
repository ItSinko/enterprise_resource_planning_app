<script>
export default {
    props: ["file", "format"],
    methods: {
        addFile() {
            this.file.push({
                file: null,
            });
        },
        removeFile(index) {
            this.file.splice(index, 1);
        },
        handleFileChange(idx) {
            const selectedFile = event.target.files[0];
            if (selectedFile) {
                const allowedFormats = this.format
                    ? this.format.split(",").map((format) => format.trim())
                    : [
                        'audio',
                        'image',
                        'video',
                        'application',
                      ];
                const fileExtension = selectedFile.type
                    .split("/")
                    .shift()
                    .toLowerCase();
                
                console.log(selectedFile);

                if (allowedFormats.find((format) => format === fileExtension)) {
                    this.file[idx].file = selectedFile;
                    this.file[idx].name = selectedFile.name;
                } else {
                    alert("Format file tidak sesuai");
                    this.file[idx] = null;
                    this.$refs.file[idx].value = "";
                }
            }
        },
    },
    computed: {
        isFormEmpty() {
            const isFormEmpty = Object.values(this.file).some((field) => {
                if (Array.isArray(field)) {
                    // If the field is an array, check if any of its items have a null file
                    return field.some((item) => item.file === null);
                } else {
                    // If the field is not an array, check if it has a null file
                    return field.file === null;
                }
            });
            return isFormEmpty;
        },
    },
};
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <button
                    class="btn btn-outline-primary"
                    @click="addFile"
                    :disabled="isFormEmpty"
                >
                    Tambah File
                </button>
            </div>
        </div>
        <div class="row my-1" v-for="(data, idx) in file" :key="idx">
            <div class="col-12">
                <input
                    type="file"
                    class="form-control"
                    :accept="format"
                    @change="handleFileChange(idx)"
                    ref="file"
                />
            </div>
        </div>
    </div>
</template>
