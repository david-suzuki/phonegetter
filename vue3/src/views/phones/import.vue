<template>

  <!--begin::Basic info-->
  <div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div
      class="card-header border-0 cursor-pointer"
      role="button"
      data-bs-toggle="collapse"
      data-bs-target="#kt_account_profile_details"
      aria-expanded="true"
      aria-controls="kt_account_profile_details"
    >
      <!--begin::Card title-->
      <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ translate("selectExcel") }}</h3>
      </div>
      <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div id="kt_account_profile_details" class="collapse show">
        <!--begin::Card body-->
        <div class="card-body border-top p-9">
          <!--begin::Input group-->
          <div class="row mb-6">
            <div class="col-lg-1"></div>
            <!--begin::Label-->
            <label class="col-lg-1 col-form-label fw-bold fs-6">{{ translate("excelFile") }}</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-3">
              <!--begin::Image input-->
              <div
                class="image-input image-input-outline"
                data-kt-image-input="true"
                style="background-image: url(media/avatars/blank.png)"
              >
                <!--begin::Preview existing avatar-->
                <div 
                  class="image-input-wrapper w-250px h-45px"
                >
                  <div v-if="isFileSelected" class="d-flex justify-content-center">
                    <div class="symbol symbol-25px mb-6" style="margin-top:5px">
                      <img src="/media/svg/files/doc.svg" alt="" />
                    </div>
                    <div class="fs-5 fw-bolder mb-2" style="margin-left: 10px; margin-top:5px">
                      {{ selectedFileName }}
                    </div>
                  </div>
                </div>
                <!--end::Preview existing avatar-->

                <!--begin::Label-->
                <label
                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                  data-kt-image-input-action="change"
                  data-bs-toggle="tooltip"
                  title="Change file"
                >
                  <i class="bi bi-pencil-fill fs-7"></i>

                  <!--begin::Inputs-->
                  <input type="file" accept=".xlsx" ref="file" @change="selectFile"/>
                  <input type="hidden" name="avatar_remove" />
                  <!--end::Inputs-->
                </label>
                <!--end::Label-->

                <!--begin::Remove-->
                <span
                  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                  data-kt-image-input-action="remove"
                  data-bs-toggle="tooltip"
                  @click="removeFile()"
                  title="Remove file"
                >
                  <i class="bi bi-x fs-2"></i>
                </span>
                <!--end::Remove-->
              </div>
            </div>
            <!--end::Col-->

            <div class="col-lg-3">
              <!--begin::Actions-->
              <div class="px-9">
                <button
                  id="uploadBtn"
                  class="btn btn-primary"
                  :disabled="!isFileSelected" 
                  @click="uploadFile"
                >
                  <span class="indicator-label">
                    {{ translate('import') }}
                  </span>
                  <span class="indicator-progress">
                    {{ translate('pleaseWait') }}
                    <span
                      class="spinner-border spinner-border-sm align-middle ms-2"
                    ></span>
                  </span>
                </button>
              </div>
              <!--end::Actions-->
            </div>
          </div>
          <!--end::Input group-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Basic info-->
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";
import axios from 'axios';
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "documents",
  components: {
  },
  setup() {
    const { t, te } = useI18n();
    onMounted(() => {
      setCurrentPageBreadcrumbs("importExcel", ["phoneNumbers"]);
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      translate
    };
  },
  methods: {
    removeFile() {
      this.isFileSelected = false;
      this.selectedFiles = {};
    },
    selectFile(event) {
      // this.selectedFiles = this.$refs.file.files;
      this.isFileSelected = true;
      this.selectedFiles = event.target.files;
      this.selectedFileName = this.selectedFiles[0].name;

    },
    uploadFile() {
      const uploadBtn = document.getElementById('uploadBtn')!;
      uploadBtn.setAttribute("data-kt-indicator", "on");

      const currentFile = this.selectedFiles[0];
      
      const formData = new FormData();
      formData.append("file", currentFile);

      const token = localStorage.getItem('id_token');
      const headers = { 
        Authorization: 'Bearer ' + token,
        "Content-Type": "multipart/form-data"
      };

      axios
      .post("http://localhost:8000/phones/import", formData, { headers: headers })
      .then((response)=>
      { 
        if (response.data == 'success') {
          Swal.fire({
            text: "Excel file importing success.",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Success!",
            customClass: {
              confirmButton: "btn fw-bold btn-light-primary"
            }
          });
          uploadBtn.setAttribute("data-kt-indicator", "off");
        } else {
          Swal.fire({
            text: response.data,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Error!",
            customClass: {
              confirmButton: "btn fw-bold btn-light-danger"
            }
          });
          uploadBtn.setAttribute("data-kt-indicator", "off");  
        }
      }).catch((err) => {
        Swal.fire({
          text: "Excel file importing fail.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
        uploadBtn.setAttribute("data-kt-indicator", "off");
      });
    }
  },
  data() {
    return {
      selectedFiles: {},
      selectedFileName: "",
      isFileSelected: false
    }
  }
});
</script>
