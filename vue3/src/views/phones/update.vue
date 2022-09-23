<template>
  <!--begin::Basic info-->
  <div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div
      class="card-header border-0 cursor-pointer"
    >
      <!--begin::Card title-->
      <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ translate("updatePhone") }}</h3>
      </div>
      <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div class="collapse show">
      <!--begin::Form-->
      <Form
        class="form"
        novalidate="novalidate"
        @submit="saveChanges()"
        :validation-schema="phoneDetailsValidator"
      >
        <!--begin::Card body-->
        <div class="card-body border-top p-9">

          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >{{ translate("phoneNumber") }}</label
            >
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="pnumber"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('plPhoneNumber')"
                v-model="phoneDetails.pnumber"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="pnumber" />
                </div>
              </div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-bold fs-6">
              {{ translate("clientName") }}
            </label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="cname"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('plClientName')"
                v-model="phoneDetails.cname"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="cname" />
                </div>
              </div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-bold fs-6"
              >{{ translate("status") }}</label
            >
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <select
                name="status"
                class="form-select form-select-solid"
                v-model="phoneDetails.status"
              >
                <option value="normal">Normal</option>
                <option value="locked">Locked</option>
              </select>
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="status" />
                </div>
              </div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->

        </div>
        <!--end::Card body-->

        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">

          <button
            type="submit"
            id="phone_detail_submit"
            ref="submitButton1"
            class="btn btn-primary"
          >
            <span class="indicator-label">
              {{ translate("saveChanges") }}
            </span>
            <span class="indicator-progress">
              {{ translate("pleaseWait") }}
              <span
                class="spinner-border spinner-border-sm align-middle ms-2"
              ></span>
            </span>
          </button>
        </div>
        <!--end::Actions-->
      </Form>
      <!--end::Form-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Basic info-->
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { ErrorMessage, Field, Form } from "vee-validate";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";
import axios from "axios";
import { useI18n } from "vue-i18n"; 

interface PhoneDetails {
  pnumber: string;
  cname: string;
  status: string;
}

export default defineComponent({
  name: "phone-update",
  components: {
    ErrorMessage,
    Field,
    Form
  },
  props: ['id'],

  setup() {
    const { t, te } = useI18n();

    const phoneDetailsValidator = Yup.object().shape({
      pnumber: Yup.string()
        .required()
        .label("Phone Number"),
      cname: Yup.string()
        .required()
        .label("Client Name")
    });

    onMounted(() => {
      setCurrentPageBreadcrumbs("update", ["phoneNumbers"]);
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      phoneDetailsValidator,
      translate
    };
  },
  
  mounted() {
    const phoneId = this.id;
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };
    axios
    .get("http://localhost:8000/phones/fetch/" + phoneId, { headers: authHeader })
    .then((response)=>
    { 
      this.phoneDetails = response.data;
    }).catch((err) => {
      console.log(err);
    });
  },

  methods: {
    saveChanges() {
      const submitBtn = document.getElementById('phone_detail_submit')!;
      submitBtn.setAttribute("data-kt-indicator", "on");

      const phoneId = this.id;
      const phoneData = {id: this.id, phone:this.phoneDetails};

      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };
      
      axios
      .post("http://localhost:8000/phones/update", phoneData, { headers: authHeader })
      .then((response)=>
      { 
        Swal.fire({
          text: "Phone number update success.",
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "Success!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-primary"
          }
        });
        submitBtn.removeAttribute("data-kt-indicator");
      }).catch((err) => {
        Swal.fire({
          text: "Phone number update fail.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
        submitBtn.removeAttribute("data-kt-indicator");
      });
    }
  },

  data() {
    return {
      phoneDetails: {}
    }
  }
});


</script>

