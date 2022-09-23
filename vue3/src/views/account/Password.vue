<template>

  <!--begin::Sign-in Method-->
  <div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div
      class="card-header border-0 cursor-pointer"
      role="button"
      data-bs-toggle="collapse"
      data-bs-target="#kt_account_signin_method"
    >
      <div class="card-title m-0">
        <h3 class="fw-boldest m-0">{{ translate("password") }}</h3>
      </div>
    </div>
    <!--end::Card header-->

    <!--begin::Content-->
    <div id="kt_account_signin_method" class="collapse show">
      <!--begin::Card body-->
      <div class="card-body border-top p-9">

        <!--begin::Password-->
        <div class="d-flex flex-wrap align-items-center mb-8">
          <div
            id="kt_signin_password"
            :class="{ 'd-none': passwordFormDisplay }"
          >
            <div class="fs-4 fw-boldest mb-1">{{ translate("password") }}</div>
            <div class="fs-6 fw-bold text-gray-600">************</div>
          </div>
          <div
            id="kt_signin_password_edit"
            class="flex-row-fluid"
            :class="{ 'd-none': !passwordFormDisplay }"
          >
            <div class="fs-6 fw-bold text-gray-600 mb-4">
              {{ translate("passwordSpecification") }}
            </div>

            <!--begin::Form-->
            <Form
              id="kt_signin_change_password"
              class="form"
              novalidate="novalidate"
              @submit="updatePassword()"
              :validation-schema="changePassword"
            >
              <div class="row mb-6">
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label
                      for="currentpassword"
                      class="form-label fs-6 fw-bolder mb-3"
                      >{{ translate("currentPassword") }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="currentpassword"
                      id="currentpassword"
                      v-model="currentpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="currentpassword" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label
                      for="newpassword"
                      class="form-label fs-6 fw-bolder mb-3"
                      >{{ translate("newPassword") }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="newpassword"
                      id="newpassword"
                      v-model="newpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="newpassword" />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="fv-row mb-0">
                    <label
                      for="confirmpassword"
                      class="form-label fs-6 fw-bolder mb-3"
                      >{{ translate("confirmNewPassword") }}</label
                    >
                    <Field
                      type="password"
                      class="form-control form-control-lg form-control-solid fw-bold fs-6"
                      name="confirmpassword"
                      id="confirmpassword"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="confirmpassword" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex">
                <button
                  id="kt_password_submit"
                  type="submit"
                  ref="updatePasswordButton"
                  class="btn btn-primary  me-2 px-6"
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
                <button
                  id="kt_password_cancel"
                  type="button"
                  @click="passwordFormDisplay = !passwordFormDisplay"
                  class="btn btn-color-gray-400 btn-active-light-primary px-6"
                >
                  {{ translate("cancel") }}
                </button>
              </div>
            </Form>
            <!--end::Form-->
          </div>
          <div
            id="kt_signin_password_button"
            class="ms-auto"
            :class="{ 'd-none': passwordFormDisplay }"
          >
            <button
              @click="passwordFormDisplay = !passwordFormDisplay"
              class="btn btn-light fw-boldest"
            >
              {{ translate("resetPassword") }}
            </button>
          </div>
        </div>
        <!--end::Password-->
      </div>
      <!--end::Card body-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Sign-in Method-->
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { ErrorMessage, Field, Form } from "vee-validate";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";
import { useStore } from "vuex";
import { Actions } from "@/store/enums/StoreEnums.ts";
import Swal from "sweetalert2/dist/sweetalert2.js";
import * as Yup from "yup";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "account-password",
  components: {
    ErrorMessage,
    Field,
    Form
  },
  setup() {
    const { t, te } = useI18n();
    const store = useStore();
    const updatePasswordButton = ref<HTMLElement | null>(null);

    const passwordFormDisplay = ref(false);

    const changePassword = Yup.object().shape({
      currentpassword: Yup.string()
        .required()
        .label("Current password"),
      newpassword: Yup.string()
        .min(4)
        .required()
        .label("Password"),
      confirmpassword: Yup.string()
        .min(4)
        .required()
        .oneOf([Yup.ref("newpassword"), null], "Passwords must match")
        .label("Password Confirmation")
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    const currentpassword = ref("");
    const newpassword = ref("");

    const updatePassword = () => {
      if (updatePasswordButton.value) {
        // Activate indicator
        updatePasswordButton.value.setAttribute("data-kt-indicator", "on");

        // getting id of user
        const user = store.getters.currentUser;
        const uid = user.id;

        const pass = { uid:uid, newpass: newpassword.value, curpass: currentpassword.value };

        store.dispatch(Actions.UPDATE_PASSWORD, pass)
          .then((data) => {
            if (data == "success") { 
              Swal.fire({
                text: "Password is successfully changed!",
                icon: "success",
                confirmButtonText: "Ok",
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-light-primary"
                }
              }).then(() => {
                passwordFormDisplay.value = false;
                updatePasswordButton.value?.removeAttribute("data-kt-indicator");
              });
            } else if (data == "current_pass_error") {
              Swal.fire({
                text: "Current password incorrect",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Error!",
                customClass: {
                  confirmButton: "btn btn-light-danger"
                }
              });
              updatePasswordButton.value?.removeAttribute("data-kt-indicator");
            }
          }).catch(() => {
            Swal.fire({
              text: "Password update is failed",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Error!",
              customClass: {
                confirmButton: "btn btn-light-danger"
              }
            });
            updatePasswordButton.value?.removeAttribute("data-kt-indicator");
          })
      }
    };

    onMounted(() => {
      setCurrentPageBreadcrumbs("changePassword", ["account"]);
    });

    return {
      passwordFormDisplay,
      changePassword,
      updatePasswordButton,
      updatePassword,
      newpassword,
      currentpassword,
      translate
    };
  }
});
</script>
