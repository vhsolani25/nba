<template>
    <section class="content-wrapper" style="min-height: 960px;">
        <section class="content-header">
            <h1>Images</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <form @submit.prevent="submitForm" novalidate>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Create</h3>
                            </div>

                            <div class="box-body">
                                <back-buttton></back-buttton>
                            </div>

                            <bootstrap-alert />

                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name">Name *</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            name="name"
                                            placeholder="Enter Name *"
                                            :value="item.name"
                                            @input="updateName"
                                            >
                                </div>
                                <div class="form-group">
                                    <label for="image">Image *</label>
                                    <input
                                            type="file"
                                            class="form-control"
                                            @change="updateImage"
                                    >
                                    <ul v-if="item.image" class="list-unstyled">
                                        <li>
                                            {{ item.image.name || item.image.file_name }}
                                            <button class="btn btn-xs btn-danger"
                                                    type="button"
                                                    @click="removeImage"
                                            >
                                                Remove file
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="order">Order *</label>
                                    <input
                                            type="number"
                                            min="1"
                                            max="11"
                                            class="form-control"
                                            name="order"
                                            placeholder="Enter Order *"
                                            :value="item.order"
                                            @input="updateOrder"
                                            >
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="status"
                                                    :value="item.status"
                                                    :checked="item.status === '1'"
                                                    @change="updateStatus('1')"
                                                    >
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input
                                                    type="radio"
                                                    name="status"
                                                    :value="item.status"
                                                    :checked="item.status === '0'"
                                                    @change="updateStatus('0')"
                                                    >
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <vue-button-spinner
                                        class="btn btn-primary btn-sm"
                                        :isLoading="loading"
                                        :disabled="loading"
                                        >
                                    Save
                                </vue-button-spinner>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </section>
</template>


<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      // Code...
    };
  },
  computed: {
    ...mapGetters("ImagesSingle", ["item", "loading"])
  },
  created() {
    this.item.status = "1";
  },
  destroyed() {
    this.resetState();
  },
  methods: {
    ...mapActions("ImagesSingle", [
      "storeData",
      "resetState",
      "setName",
      "setImage",
      "setOrder",
      "setStatus"
    ]),
    updateName(e) {
      this.setName(e.target.value);
    },
    removeImage(e, id) {
      this.$swal({
        title: "Are you sure?",
        text: "To fully delete the file submit the form.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        confirmButtonColor: "#dd4b39",
        focusCancel: true,
        reverseButtons: true
      }).then(result => {
        if (typeof result.dismiss === "undefined") {
          this.setImage("");
        }
      });
    },
    updateImage(e) {
      this.setImage(e.target.files[0]);
      this.$forceUpdate();
    },
    updateOrder(e) {
      this.setOrder(e.target.value);
    },
    updateStatus(value) {
      this.setStatus(value);
    },
    submitForm() {
      this.storeData()
        .then(() => {
          this.$router.push({ name: "images.index" });
          this.$eventHub.$emit("create-success");
        })
        .catch(error => {
          console.log(error.response)
        });
    }
  }
};
</script>


<style scoped>
</style>
