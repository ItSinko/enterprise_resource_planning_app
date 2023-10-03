<script>
export default {
    props: {
        image: {
            type: File,
            default: '/assets/image/user/blank.png'
        }
    },
    data() {
        return {
            imageUrl: '/assets/image/user/blank.png'
        }
    },
    methods: {
        selectFile() {
            this.$refs.fileInput.click();
        },
        previewImage() {
            const file = this.$refs.fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                    this.$emit('fileSelected', file);
                };
                reader.readAsDataURL(file);
            } else {
                this.imageUrl = '/assets/image/user/blank.png';
            }
        },
        removeImage() {
            this.imageUrl = '/assets/image/user/blank.png';
            this.$refs.fileInput.value = '';
            this.$emit('fileRemoved');
        },
    }
}
</script>

<template>
    <div class="kt-avatar">
        <div class="kt-avatar__holder">
            <img class="kt-avatar__image" :src="imageUrl" alt="Avatar">
        </div>

        <label class="kt-avatar__upload-btn btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow">
            <i class="fa fa-pen icon-sm text-muted"></i>
            <input type="file" name="profile_avatar" ref="fileInput" accept=".png, .jpg, .jpeg" @change="previewImage" />
            <input type="hidden" name="profile_avatar_remove" />
        </label>
        <button class="kt-avatar__remove-btn btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
            v-if="imageUrl != '/assets/image/user/blank.png'" @click="removeImage">
            <i class="fa fa-times icon-sm text-muted"></i>
        </button>

    </div>
</template>

<style>
.kt-avatar {
    position: relative;
    display: inline-block;
    border-radius: 50%;
}

.kt-avatar__holder {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.kt-avatar__image {
    object-fit: cover;
    border-radius: 50%;
    object-position: top center;
    /* Maintain aspect ratio and cover the container */
}

.kt-avatar__upload-btn {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #ffffff;
    border: 2px solid #e4e6ef;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.kt-avatar__cancel-btn,
.kt-avatar__remove-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: #ffffff;
    border: 2px solid #e4e6ef;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.kt-avatar__upload-btn:hover,
.kt-avatar__cancel-btn:hover,
.kt-avatar__remove-btn:hover {
    background-color: #f3f4f9;
    border-color: #d1d3e0;
}

input[type="file"] {
    display: none;
}

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) {
    .kt-avatar__image {
        width: 50px;
        height: 50px;
    }
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) {
    .kt-avatar__image {
        width: 50px;
        height: 50px;
    }
}

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
    .kt-avatar__image {
        width: 125px;
        height: 125px;
    }

    .kt-avatar__upload-btn {
        transform: translate(-50% 40%);
    }

    .kt-avatar__cancel-btn,
    .kt-avatar__remove-btn {
        transform: translate(-50% 30%);
    }
}

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) {
    .kt-avatar__image {
        width: 200px;
        height: 200px;
    }

    .kt-avatar__upload-btn {
        transform: translate(-65%);
    }

    .kt-avatar__cancel-btn,
    .kt-avatar__remove-btn {
        transform: translate(-65%);
    }
}


/* Additional styling can be added as needed */
</style>
