<template>
    <input
        ref="FileInput"
        type="file"
        id="image"
        accept="image/*"
        class="hidden"
        @change="onFileSelect"
    />
</template>

<script>
export default {
    name: "ImageUpload",
    data() {
        return {
            image: "",
            file: "",
        };
    },
    methods: {
        openImageUpload() {
            this.$refs.FileInput.value = "";
            this.$refs.FileInput.click();
        },
        onFileSelect(e) {
            const file = e.target.files[0];
            this.file = file;
            this.image = URL.createObjectURL(file);
            this.$emit("get-image", this.image, file);
        },
        remove() {
            this.image = "";
            this.$emit("get-image", this.image, "");
        },
    },
};
</script>

<style scoped>
/* (1366x768) WXGA Display */

@media screen and (min-width: 1366px) and (max-width: 1919px) {
}

@media (min-width: 1198px) and (max-width: 1365.9px) {
}

/* Normal desktop :992px. */

@media (min-width: 992px) and (max-width: 1197px) {
}

/* Normal desktop :991px. */

@media (min-width: 768px) and (max-width: 991px) {
}

/* small mobile :576px. */

@media (min-width: 576px) and (max-width: 767px) {
}

/* extra small mobile 320px. */

@media (max-width: 575px) {
}

/* Large Mobile :480px. */

@media only screen and (min-width: 480px) and (max-width: 575px) {
}

@media only screen and (max-width: 575px) and (min-width: 480px) {
}
</style>
