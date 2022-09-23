<template>

  <div class="row gy-5 gx-xl-8">
    <UsersTable
      widget-classes="card-xxl-stretch mb-5 mb-xl-8"
    ></UsersTable>
  </div>

</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import UsersTable from "@/components/widgets/table/UsersTable.vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "users-list",
  components: {
    UsersTable
  },
  setup() {
    onMounted(() => {
      setCurrentPageBreadcrumbs("list", ["users"]);
    });
  }
});
</script>
