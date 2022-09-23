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
        <h3 class="fw-bolder m-0">{{ translate("profilDetails") }}</h3>
      </div>
      <!--end::Card title-->
    </div>
    <!--begin::Card header-->

    <!--begin::Content-->
    <div id="kt_account_profile_details" class="collapse show">
      <!--begin::Form-->
      <Form
        class="form"
        novalidate="novalidate"
        @submit="saveChanges1()"
        :validation-schema="profileDetailsValidator"
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
                placeholder="Admin or User name"
                v-model="profileDetails.name"
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
                title="Account must be unique."
              ></i>
            </label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                type="text"
                name="account"
                class="form-control form-control-lg form-control-solid"
                placeholder="Admin or User account"
                v-model="profileDetails.account"
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
              >{{ translate("role") }}</label
            >
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
              <Field
                as="select"
                name="role"
                class="form-select form-select-solid form-select-lg fw-bold"
                v-model="profileDetails.role"
              >
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </Field>
              <div class="fv-plugins-message-container">
                <div class="fv-help-block">
                  <ErrorMessage name="role" />
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
import { useStore } from "vuex";
import { Actions } from "@/store/enums/StoreEnums.ts";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";
import axios from "axios";
import { useI18n } from "vue-i18n";

interface ProfileDetails {
  name: string;
  account: string;
  role: string;
}

export default defineComponent({
  name: "account-settings",
  components: {
    ErrorMessage,
    Field,
    Form
  },
  setup() {
    const { t, te } = useI18n();
    const store = useStore();
    const submitButton1 = ref<HTMLElement | null>(null);

    const profileDetailsValidator = Yup.object().shape({
      name: Yup.string()
        .required()
        .label("Name"),
      account: Yup.string()
        .required()
        .label("Account")
    });

    const user = store.getters.currentUser;

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    const profileDetails = ref<ProfileDetails>({
      name: user.uname,
      account: user.account,
      role: user.role
    });

    const saveChanges1 = () => {
      const uname = profileDetails.value.name;
      const account = profileDetails.value.account;
      const role = profileDetails.value.role;

      const uid = user.id;

      if (submitButton1.value) {
        // Activate indicator
        submitButton1.value.setAttribute("data-kt-indicator", "on");

        const user = { uid:uid, uname: uname, account: account, role: role };

        store.dispatch(Actions.UPDATE_USER, user)
          .then(() => {
            Swal.fire({
              text: "Account updated success.",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Success!",
              customClass: {
                confirmButton: "btn fw-bold btn-light-primary"
              }
            });
            submitButton1.value?.removeAttribute("data-kt-indicator"); 
          }).catch(() => {
            Swal.fire({
              text: "Account updated fail.",
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
      setCurrentPageBreadcrumbs("settings", ["account"]);
    });

    return {
      submitButton1,
      saveChanges1,
      profileDetails,
      profileDetailsValidator,
      translate
    };
  }
});


</script>
