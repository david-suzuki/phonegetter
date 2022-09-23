<template>

  <div class="row gy-5 gx-xl-8">

    <!--begin::Button-->
    <div class="d-flex justify-content-start px-9">
      <button
        type="button"
        ref="pickButton"
        class="btn btn-primary"
        @click = "getNumbers"
        :disabled="phonesLength"
      >
        <span class="indicator-label">
          {{ translate('pickNumbers') }}
        </span>
        <span class="indicator-progress">
          {{ translate('pleaseWait') }}
          <span
            class="spinner-border spinner-border-sm align-middle ms-2"
          ></span>
        </span>
      </button>
    </div>
    <!--end::Button-->

    <!--begin::Tables-->
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
      <!--begin::Header-->
      <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bolder fs-3 mb-1">{{ translate('availablePhones') }}</span>
        </h3>
        <div class="d-flex align-items-center position-relative me-4" style="display: flex; justify-content: flex-end">
          <input
            type="text"
            id="kt_filter_search"
            class="form-control form-control-white form-control-sm w-150px ps-9"
            :placeholder="translate('search')"
            style="background: #E4E6EF;"
            v-model="filter"
          />
          <span class="svg-icon svg-icon-3 position-absolute ms-3" style="margin-right:10px">
            <inline-svg src="/media/icons/duotone/General/Search.svg"/>
          </span>
        </div>
      </div>
      <!--end::Header-->

      <!--begin::Body-->
      <div class="card-body py-3">
        <!--begin::Table container-->
        <div class="table-responsive">
          <!--begin::Table-->
          <table
            class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4"
          >
            <!--begin::Table head-->
            <thead>
              <tr class="fw-bolder text-muted">
                <th class="min-w-100px">No</th>
                <th class="min-w-150px">{{ translate('phoneNumber') }}</th>
                <th class="min-w-150px">{{ translate('clientName') }}</th>
                <th class="min-w-150px">Tag</th>
                <th class="min-w-50px text-end">{{ translate('actions') }}</th>
              </tr>
            </thead>
            <!--end::Table head-->

            <!--begin::Table body-->
            <tbody>
              <template v-for="(phone, index) in pagedRows" :key="phone.id">
                <tr>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="d-flex justify-content-start flex-column">
                        <a
                          href="#"
                          class="text-dark fw-bolder text-hover-primary fs-6"
                          >{{ index + 1 }}</a
                        >
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="d-flex justify-content-start flex-column">
                        <a
                          href="#"
                          class="text-dark fw-bolder text-hover-primary fs-6"
                          >{{ phone.pnumber }}</a
                        >
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="d-flex justify-content-start flex-column">
                        <a
                          href="#"
                          class="text-dark fw-bolder text-hover-primary fs-6"
                          >{{ phone.cname }}</a
                        >
                      </div>
                    </div>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <div class="d-flex justify-content-start flex-column">
                        <!--begin::Input-->
                        <el-select
                          class="form-select-solid"
                          placeholder="Select option"
                          v-model="tagids[index]"
                        >
                          <el-option :label="tag.tname" :value="tag.id" v-for="tag in tags" :key="tag.id">{{ tag.tname }}</el-option>
                        </el-select>
                      </div>
                    </div>
                  </td>

                  <td class="text-end">
                    <a
                      href="javascript:void(0)" @click="markPhone(index, phone.id)"
                      class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                    >
                      <span class="svg-icon svg-icon-3">
                        <inline-svg src="/media/icons/duotone/Design/Edit.svg" />
                      </span>
                    </a>
                  </td>

                </tr>
              </template>
            </tbody>
            <!--end::Table body-->
          </table>
          <!--end::Table-->
        </div>
        <!--end::Table container-->
        
      </div>
      <!--begin::Body-->
    </div>
    <!--end::Tables-->

    <!--begin::Pagination-->
    <div class="d-flex flex-stack flex-wrap">
      <div class="fs-6 fw-bold text-gray-700">
      </div>

      <!--begin::Pages-->
      <ul class="pagination">
        <li class="page-item previous">
          <a href="javascript:void(0)" class="page-link" @click="prevPage"><i class="previous"></i></a>
        </li>

        <li class="page-item">
          <span href="#" class="page-link">{{ currentPage }}</span>
        </li>

        <li class="page-item next">
          <a href="javascript:void(0)" class="page-link" @click="nextPage"><i class="next"></i></a>
        </li>
      </ul>
      <!--end::Pages-->
    </div>
    <!--end::Pagination-->

  </div>

</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { setCurrentPageBreadcrumbs } from "@/core/helpers/breadcrumb";
import axios from 'axios';
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useStore } from "vuex"; 
import { useI18n } from "vue-i18n";

interface Phone {
  pnumber: string;
  cname: string;
}

export default defineComponent({
  name: "picking-list",
  components: {},

  setup() {
    const { t, te } = useI18n();
    const store = useStore();

    // getting user's pickable amount and id
    const user = store.getters.currentUser;
    const pickable = user.pickable;
    const userId = user.id;

    // breadcramb
    onMounted(() => {
      setCurrentPageBreadcrumbs("picking", []);
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      userId,
      pickable,
      translate
    };
  },

  mounted(){
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    // fetching list of tags
    axios
    .get("http://localhost:8000/tags/list", { headers: authHeader })
    .then((response)=>
    { 
      this.tags = response.data;
    }).catch((err) => {
      console.log(err);
    });

    // fetching list of picking numbers
    axios
    .get("http://localhost:8000/picking/current", { headers: authHeader })
    .then((response)=>
    { 
      this.phones = response.data;
    }).catch((err) => {
      console.log(err);
    });    
  },

  methods: {

    nextPage:function() {
      if ((this.currentPage*this.pageSize) < this.phones.length) this.currentPage++;
    },

    prevPage:function() {
      if (this.currentPage > 1) this.currentPage--;

    },

    // when clicking the button, fetching the list of phone numbers
    getNumbers() {
      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };

      axios
      .get("http://localhost:8000/picking/list/" + this.pickable, { headers: authHeader })
      .then((response)=>
      { 
        this.phones = response.data;
      }).catch((err) => {
        console.log(err);
      });
    },

    // Marking tag on phone 
    markPhone(index, id) {
      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };

      // If tag is not selected
      const tagId = this.tagids[index];
      if (tagId == undefined) {
        Swal.fire({
          text: "Please select tag.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
        return;
      }

      const data = {phoneId: id, tagId: tagId, userId: this.userId};

      // storing marked phone number to database
      axios
      .post("http://localhost:8000/picking/mark", data, { headers: authHeader })
      .then((response) => { 
        // removing the marked phone number from current list
        this.phones.splice(index, 1);
        // initialize the Tag select whenever removing marked phone number
        this.tagids = [];
      }).catch((err) => {
        Swal.fire({
          text: "Phone delete fail.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
      });
    }
  },

  computed: {
    pagedRows():Phone[] {
      return this.phones.filter((row, index) => {
        const start = (this.currentPage-1) * this.pageSize;
        const end = this.currentPage * this.pageSize;

        if (index >= start && index < end) return true;
      })
      .filter((row: Phone) => {
        const pnumber = row.pnumber.toString().toLowerCase();
        const cname = row.cname.toString().toLowerCase();
        const searchTerm = this.filter.toLowerCase();

        return pnumber.includes(searchTerm) ||
          cname.includes(searchTerm);
      });
    },
    // This value is used for disabling or enabling button
    phonesLength(): number {
      return this.phones.length;
    }
  },
  
  data() {
    return {
      phones: [],
      tags: [],
      pageSize:5,
      currentPage:1,
      filter:'',
      tagids: []
    }
  }
});
</script>
