<script>
export default {
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
                };
                reader.readAsDataURL(file);
            } else {
                this.imageUrl = '/assets/image/user/blank.png';
            }
        },
        removeImage() {
            this.imageUrl = '/assets/image/user/blank.png';
            this.$refs.fileInput.value = '';
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
            @click="removeImage">
            <i class="fa fa-trash icon-sm text-muted"></i>
        </button>

    </div>
</template>

<style>
.kt-avatar {
    position: relative;
    display: inline-block;
}

.kt-avatar__holder {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 50%;
    object-fit: cover;
}

.kt-avatar__image {
    width: 500px;
    height: 500px;
    object-fit: cover;
    border-radius: 50%;
    /* Maintain aspect ratio and cover the container */
}

.kt-avatar__upload-btn,
.kt-avatar__cancel-btn,
.kt-avatar__remove-btn {
    position: absolute;
    bottom: 0;
    left: 0;
    transform: translate(-50%, 50%);
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

/* screen large monitor */
@media (min-width: 1200px) {
    .kt-avatar__holder {
        width: 500px;
        height: 500px;
    }
}
/* screen medium monitor */
@media (min-width: 992px) and (max-width: 1199px) {
    .kt-avatar__holder {
        width: 100px;
        height: 100px;
    }
}

/* Additional styling can be added as needed */
</style>
