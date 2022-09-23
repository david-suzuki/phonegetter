<template>
  <!--begin::Basic info-->
  <div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div
      class="card-header border-0 cursor-pointer"
    >
      <!--begin::Card title-->
      <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ translate("addUser") }}</h3>
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
        @submit="saveChanges1()"
        :validation-schema="userDetailsValidator"
      >
        <!--begin::Card body-->
        <div class="card-body border-top p-9">

          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label required fw-bold fs-6"
              >{{ translate("fullName") }}</label
            >
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="name"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('userFullname')"
                v-model="userDetails.name"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="name" />
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
              <span class="required">{{ translate("account") }}</span>
              <i
                class="fas fa-exclamation-circle ms-1 fs-7"
                data-bs-toggle="tooltip"
                title="Account must be unique"
              ></i>
            </label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="account"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('userAccount')"
                v-model="userDetails.account"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="account" />
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
              <Field
                as="select"
                name="status"
                class="form-select form-select-solid form-select-lg fw-bold"
                v-model="userDetails.status"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </Field>
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="status" />
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
              {{ translate("password") }}
            </label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="password"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('userPassword')"
                v-model="userDetails.password"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="password" />
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
              {{ translate("pickableAmount") }}
            </label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="number"
                name="pickable"
                class="form-control form-control-lg form-control-solid"
                :placeholder="translate('userPickableAmount')"
                v-model="userDetails.pickable"
              />
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="pickable" />
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
            id="kt_account_profile_details_submit"
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

interface UserDetails {
  name: string;
  account: string;
  status: string;
  password: string;
  pickable: number;
}

export default defineComponent({
  name: "user-create",
  components: {
    ErrorMessage,
    Field,
    Form
  },
  setup() {
    const { t, te } = useI18n();
    const submitButton1 = ref<HTMLElement | null>(null);

    const userDetailsValidator = Yup.object().shape({
      name: Yup.string()
        .required()
        .label("Name"),
      account: Yup.string()
        .required()
        .label("Account"),
      password: Yup.string()
        .min(4)
        .required()
        .label("Password"),
    });

    const userDetails = ref<UserDetails>({
      name: '',
      account: '',
      status: 'Active',
      password: '',
      pickable: -1,
    });

    const saveChanges1 = () => {
      const uname = userDetails.value.name;
      const account = userDetails.value.account;
      const status = userDetails.value.status;
      const password = userDetails.value.password;
      const pickable = userDetails.value.pickable;

      if (submitButton1.value) {
        // Activate indicator
        submitButton1.value.setAttribute("data-kt-indicator", "on");

        const user = { uname: uname, account: account, status: status, password: password, pickable:pickable };
        
        const token = localStorage.getItem('id_token');
        const authHeader = { Authorization: 'Bearer ' + token };

        axios
        .post("http://localhost:8000/users/create", user, { headers: authHeader })
        .then((response) => { 
          Swal.fire({
            text: "User create success.",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Success!",
            customClass: {
              confirmButton: "btn fw-bold btn-light-primary"
            }
          });
          submitButton1.value?.removeAttribute("data-kt-indicator");
        }).catch((err) => {
          Swal.fire({
            text: "User create fail.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Error!",
            customClass: {
              confirmButton: "btn fw-bold btn-light-danger"
            }
          });
          submitButton1.value?.removeAttribute("data-kt-indicator");
        });
      }
    };

    onMounted(() => {
      setCurrentPageBreadcrumbs("Create", ["Users"]);
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      submitButton1,
      saveChanges1,
      userDetails,
      userDetailsValidator,
      translate
    };
  }
});


</script>

