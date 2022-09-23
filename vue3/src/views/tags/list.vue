<template>

  <div class="row gy-5 gx-xl-8">
    <TagsTable
      widget-classes="card-xxl-stretch mb-5 mb-xl-8"
    ></TagsTable>
  </div>

</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import TagsTable from "@/components/widgets/table/TagsTable.vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "tags-list",
  components: {
    TagsTable
  },
  setup() {
    onMounted(() => {
      setCurrentPageBreadcrumbs("list", ["tags"]);
    });
  }
});
</script>
