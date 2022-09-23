<template>

  <div class="row gy-5 gx-xl-8">
    <PhonesTable
      widget-classes="card-xxl-stretch mb-5 mb-xl-8"
    ></PhonesTable>
  </div>

</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import PhonesTable from "@/components/widgets/table/PhonesTable.vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "users-list",
  components: {
    PhonesTable
  },
  setup() {
    onMounted(() => {
      setCurrentPageBreadcrumbs("list", ["phoneNumbers"]);
    });
  }
});
</script>
