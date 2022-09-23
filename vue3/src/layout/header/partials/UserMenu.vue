<template>
  <!--begin::Menu-->
  <div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold py-4 fs-6 w-275px"
    data-kt-menu="true"
  >
    <!--begin::Menu item-->
    <div class="menu-item px-3">
      <div class="menu-content d-flex align-items-center px-3">
        <!--begin::Avatar-->
        <div class="symbol symbol-50px me-5">
          <img alt="Logo" src="/media/avatars/blank.png" />
        </div>
        <!--end::Avatar-->

        <!--begin::Username-->
        <div class="d-flex flex-column">
          <div class="fw-bolder d-flex align-items-center fs-5">
            {{user.uname}}
            <span
              class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2"
              >{{user.role}}</span
            >
          </div>
          <a href="#" class="fw-bold text-muted text-hover-primary fs-7"
            >{{user.account}}</a
          >
        </div>
        <!--end::Username-->
      </div>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu separator-->
    <div class="separator my-2"></div>
    <!--end::Menu separator-->

    <!--begin::Menu item-->
    <div class="menu-item px-5 my-1">
      <router-link to="/crafted/account" class="menu-link px-5">
        {{ translate('accountSettings') }}
      </router-link>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div
      class="menu-item px-5"
      data-kt-menu-trigger="hover"
      data-kt-menu-placement="left-start"
      data-kt-menu-flip="center, top"
    >
      <router-link to="" class="menu-link px-5">
        <span class="menu-title position-relative">
          {{ translate('language') }}
          <span
            class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0"
          >
            {{ currentLangugeLocale.name }}
            <img
              class="w-15px h-15px rounded-1 ms-2"
              :src="currentLangugeLocale.flag"
              alt="metronic"
            />
          </span>
        </span>
      </router-link>

      <!--begin::Menu sub-->
      <div class="menu-sub menu-sub-dropdown w-175px py-4">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
          <a
            @click="setLang('en')"
            href="#"
            class="menu-link d-flex px-5"
            :class="{ active: currentLanguage('en') }"
          >
            <span class="symbol symbol-20px me-4">
              <img
                class="rounded-1"
                src="/media/flags/united-states.svg"
                alt="metronic"
              />
            </span>
            {{ translate('English') }}
          </a>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-3">
          <a
            @click="setLang('ch')"
            href="#"
            class="menu-link d-flex px-5"
            :class="{ active: currentLanguage('ch') }"
          >
            <span class="symbol symbol-20px me-4">
              <img
                class="rounded-1"
                src="/media/flags/china.svg"
                alt="metronic"
              />
            </span>
            {{ translate('Chinese') }}
          </a>
        </div>
        <!--end::Menu item-->

      </div>
      <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-5">
      <a @click="signOut()" class="menu-link px-5">
        {{ translate('signOut') }}
      </a>
    </div>
    <!--end::Menu item-->
  </div>
  <!--end::Menu-->
</template>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { Actions } from "@/store/enums/StoreEnums.ts";

export default defineComponent({
  name: "kt-user-menu",
  components: {},
  setup(props, context) {
    const router = useRouter();
    const i18n = useI18n();
    const store = useStore();
    const { t, te } = useI18n();

    i18n.locale.value = localStorage.getItem("lang")
      ? (localStorage.getItem("lang") as string)
      : "en";

    const countries = {
      en: {
        flag: "/media/flags/united-states.svg",
        name: "English"
      },
      ch: {
        flag: "/media/flags/china.svg",
        name: "Chinese"
      }
    };

    const signOut = () => {
      store
        .dispatch(Actions.LOGOUT)
        .then(() => router.push({ name: "sign-in" }));
    };

    const user = computed(() => {
      const currentUser = store.getters.currentUser;
      return currentUser;
    });

    const setLang = lang => {
      localStorage.setItem("lang", lang);
      i18n.locale.value = lang;
    };

    const currentLanguage = lang => {
      return i18n.locale.value === lang;
    };

    const currentLangugeLocale = computed(() => {
      return countries[i18n.locale.value];
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      signOut,
      user,
      setLang,
      currentLanguage,
      currentLangugeLocale,
      countries,
      translate
    };
  }
});
</script>
