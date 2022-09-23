<template>
  <!--begin::Toolbar-->
  <div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
      <!--begin::Page title-->
      <div
        class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1"
      >
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">
          {{ title }}
        </h1>
        <!--end::Title-->

        <span
          v-if="breadcrumbs"
          class="h-20px border-gray-200 border-start mx-4"
        ></span>

        <!--begin::Breadcrumb-->
        <ul
          v-if="breadcrumbs"
          class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1"
        >
          <li class="breadcrumb-item pe-3">
            <router-link to="/dashboard" class="text-muted text-hover-primary">
              {{ translate("home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
          </li>
          <template v-for="(item, index) in breadcrumbs" :key="index">
            <li class="breadcrumb-item text-muted">
              {{ item }}
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
          </template>
          <li class="breadcrumb-item pe-3 text-dark">
            {{ title }}
          </li>
        </ul>
        <!--end::Breadcrumb-->
      </div>
      <!--end::Page title-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Toolbar-->
</template>

<script>
import { defineComponent } from "vue";
import Dropdown1 from "@/components/dropdown/Dropdown1.vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "KToolbar",
  props: {
    breadcrumbs: Array,
    title: String
  },
  components: {
  },

  setup() {
    const { t, te } = useI18n();

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      translate
    }
  }
});
</script>
