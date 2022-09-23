<template>
  <!--begin::Navbar-->
  <div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">
      <!--begin::Details-->
      <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
        
        <!--begin::Info-->
        <div class="flex-grow-1">
          <!--begin::Title-->
          <div
            class="d-flex justify-content-between align-items-start flex-wrap mb-2"
          >
            <!--begin::User-->
            <div class="d-flex flex-column">
              <!--begin::Name-->
              <div class="d-flex align-items-center mb-2">
                <span
                  class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1"
                  >{{user.uname}}</span
                >
                  <span class="svg-icon svg-icon-1 svg-icon-primary">
                    <inline-svg src="/media/icons/duotone/Design/Verified.svg" />
                  </span>

                <span
                  class="btn btn-sm btn-light-success fw-bolder ms-2 fs-8 py-1 px-3"
                  >{{user.role}}</span
                >
              </div>
              <!--end::Name-->

              <!--begin::Info-->
              <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                  <span class="svg-icon svg-icon-4 me-1">
                    <inline-svg
                      src="/media/icons/duotone/Devices/Laptop.svg"
                    />
                  </span>
                  {{user.account}}
              </div>
              <!--end::Info-->
            </div>
            <!--end::User-->

          </div>
          <!--end::Title-->
        </div>
        <!--end::Info-->
      </div>
      <!--end::Details-->

      <!--begin::Navs-->
      <div class="d-flex overflow-auto h-55px">
        <ul
          class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap"
        >
          <!--begin::Nav item-->
          <li class="nav-item">
            <router-link
              to="/crafted/account/overview"
              class="nav-link text-active-primary me-6"
              active-class="active"
            >
              {{ translate('overview') }}
            </router-link>
          </li>
          <!--end::Nav item-->
          <!--begin::Nav item-->
          <li class="nav-item">
            <router-link
              to="/crafted/account/settings"
              class="nav-link text-active-primary me-6"
              active-class="active"
            >
              {{ translate('settings') }}
            </router-link>
          </li>
          <!--end::Nav item-->
          <!--begin::Nav item-->
          <li class="nav-item">
            <router-link
              to="/crafted/account/password"
              class="nav-link text-active-primary me-6"
              active-class="active"
            >
              {{ translate('changePassword') }}
            </router-link>
          </li>
          <!--end::Nav item-->
        </ul>
      </div>
      <!--begin::Navs-->
    </div>
  </div>
  <!--end::Navbar-->
  <router-view></router-view>
</template>

<script lang="ts">
import { defineComponent, onMounted, computed } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "kt-account",
  components: {
  },
  setup() {
    const { t, te } = useI18n();
    const store = useStore();
    
    const user = computed(() => {
      return store.getters.currentUser;
    })

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    onMounted(() => {
      setCurrentPageBreadcrumbs("account", []);
    });

    return {
      user,
      translate
    }
  }
});
</script>
