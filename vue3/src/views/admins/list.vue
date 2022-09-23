<template>

  <div class="row gy-5 gx-xl-8">
    <AdminsTable
      widget-classes="card-xxl-stretch mb-5 mb-xl-8"
    ></AdminsTable>
  </div>

</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import AdminsTable from "@/components/widgets/table/AdminsTable.vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "admins-list",
  components: {
    AdminsTable
  },
  setup() {
    onMounted(() => {
      setCurrentPageBreadcrumbs("list", ["administrators"]);
    });
  }
});
</script>
