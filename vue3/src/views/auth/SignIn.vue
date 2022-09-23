<template>
  <div
    class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
    style="background-image: url('media/illustrations/progress-hd.png')"
  >
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
      <!--begin::Logo-->
      <a href="#" class="mb-12">
        <img alt="Logo" src="media/logos/logo-2-dark.svg" class="h-45px" />
      </a>
      <!--end::Logo-->

      <!--begin::Wrapper-->
      <div class="w-lg-500px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <Form
          class="form w-100"
          id="kt_login_signin_form"
          @submit="onSubmitLogin"
          :validation-schema="login"
        >
          <!--begin::Heading-->
          <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">
              Sign In to Metronic
            </h1>
            <!--end::Title-->
          </div>
          <!--begin::Heading-->

          <!--begin::Input group-->
          <div class="fv-row mb-10">
            <!--begin::Label-->
            <label class="form-label fs-6 fw-bolder text-dark">Account</label>
            <!--end::Label-->

            <!--begin::Input-->
            <Field
              class="form-control form-control-lg form-control-solid"
              type="text"
              name="account"
              autocomplete="off"
            />
            <!--end::Input-->
            <div class="fv-plugins-message-container">
              <div class="fv-help-block">
                <ErrorMessage name="account" />
              </div>
            </div>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="fv-row mb-10">
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack mb-2">
              <!--begin::Label-->
              <label class="form-label fw-bolder text-dark fs-6 mb-0"
                >Password</label
              >
              <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Input-->
            <Field
              class="form-control form-control-lg form-control-solid"
              type="password"
              name="password"
              autocomplete="off"
            />
            <!--end::Input-->
            <div class="fv-plugins-message-container">
              <div class="fv-help-block">
                <ErrorMessage name="password" />
              </div>
            </div>
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="text-center">
            <!--begin::Submit button-->
            <button
              type="submit"
              ref="submitButton"
              id="kt_sign_in_submit"
              class="btn btn-lg btn-primary w-100 mb-5"
            >
              <span class="indicator-label">
                Continue
              </span>

              <span class="indicator-progress">
                Please wait...
                <span
                  class="spinner-border spinner-border-sm align-middle ms-2"
                ></span>
              </span>
            </button>
            <!--end::Submit button-->
          </div>
          <!--end::Actions-->
        </Form>
        <!--end::Form-->
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Content-->
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { ErrorMessage, Field, Form } from "vee-validate";
import { Actions } from "@/store/enums/StoreEnums.ts";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.min.js";
import * as Yup from "yup";

export default defineComponent({
  name: "sign-in",
  components: {
    Field,
    Form,
    ErrorMessage
  },
  setup() {
    const store = useStore();
    const router = useRouter();

    const submitButton = ref<HTMLElement | null>(null);

    //Create form validation object
    const login = Yup.object().shape({
      account: Yup.string()
        .required()
        .label("Account"),
      password: Yup.string()
        .min(4)
        .required()
        .label("Password")
    });

    //Form submit function
    const onSubmitLogin = values => {
      // Clear existing errors
      store.dispatch(Actions.LOGOUT);

      if (submitButton.value) {
        // Activate indicator
        submitButton.value.setAttribute("data-kt-indicator", "on");
      }

      // Send login request
      store
        .dispatch(Actions.LOGIN, values)
        .then(() => {
            submitButton.value?.removeAttribute("data-kt-indicator");
            // Go to page after successfully login
            const user = store.getters.currentUser;
            if (user.role == 'admin')
              router.push({ name: "dashboard" });
            else
              router.push({ name: "picking-list" });
        })
        .catch(() => {
          submitButton.value?.removeAttribute("data-kt-indicator");
          Swal.fire({
            text: "Credential is incorrect",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Try again!",
            customClass: {
              confirmButton: "btn fw-bold btn-light-danger"
            }
          });
        });
    };

    return {
      onSubmitLogin,
      login,
      submitButton
    };
  }
});
</script>
