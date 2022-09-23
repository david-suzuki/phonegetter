<template>
  <!--begin::Tables Widget 9-->
  <div class="card" :class="widgetClasses">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bolder fs-3 mb-1">{{ translate("usersStatistics") }}</span>
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
              <th class="min-w-70px">No</th>
              <th class="min-w-150px">{{ translate("fullName") }}</th>
              <th class="min-w-150px">{{ translate("account") }}</th>
              <th class="min-w-100px">{{ translate("status") }}</th>
              <th class="min-w-80px">{{ translate("pickableAmount") }}</th>
              <th class="min-w-60px text-end">{{ translate("actions") }}</th>
            </tr>
          </thead>
          <!--end::Table head-->

          <!--begin::Table body-->
          <tbody>
            <template v-for="(user, index) in pagedRows" :key="user.id">
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
                        >{{ user.name }}</a
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
                        >{{ user.account }}</a
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
                        >{{ user.status }}</a
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
                        >{{ user.pickable }}</a
                      >
                    </div>
                  </div>
                </td>

                <td class="text-end">

                  <router-link
                    :to="`/users/history/${user.id}`"
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                  >
                    <span class="svg-icon svg-icon-3">
                      <inline-svg
                        src="/media/icons/duotone/General/Settings-1.svg"
                      />
                    </span>
                  </router-link>

                  <router-link
                    :to="`/users/update/${user.id}`"
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                  >
                    <span class="svg-icon svg-icon-3">
                      <inline-svg
                        src="/media/icons/duotone/Communication/Write.svg"
                      />
                    </span>
                  </router-link>

                  <a
                    href="javascript:void(0)" @click="deleteUser(index, user.id)"
                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                  >
                    <span class="svg-icon svg-icon-3">
                      <inline-svg src="/media/icons/duotone/General/Trash.svg" />
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
  <!--end::Tables Widget 9-->
  <!--begin::Pagination-->
  <div class="d-flex flex-stack flex-wrap">
    <div class="fs-6 fw-bold text-gray-700">
    </div>

    <!--begin::Pages-->
    <ul class="pagination" style="margin-right:40px">
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
</template>

<script lang="ts">

import { defineComponent, ref } from "vue";
import axios from 'axios';
import Swal from "sweetalert2/dist/sweetalert2.js"; 
import { useI18n } from "vue-i18n"; 

interface User {
  name: string;
  account: string;
  status: string;
  pickable: string;
}

export default defineComponent({
  name: "UserTable",
  components: {},
  props: {
    widgetClasses: String
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
  },
  mounted(){
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    axios
    .get("http://localhost:8000/users/list", { headers: authHeader })
    .then((response)=>
    { 
      this.users = response.data;
    }).catch((err) => {
      console.log(err);
    });
  },

  methods: {
    deleteUser(index, id) {
      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };

      axios
      .get("http://localhost:8000/users/delete/" + id, { headers: authHeader })
      .then((response) => { 
        this.users.splice(index, 1);  
      }).catch((err) => {
        Swal.fire({
          text: "User delete fail.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
      });
    },

    nextPage:function() {
      if ((this.currentPage*this.pageSize) < this.users.length) this.currentPage++;
    },

    prevPage:function() {
      if (this.currentPage > 1) this.currentPage--;
    }
  },

  computed: {
    pagedRows():User[] {
      return this.users.filter((row, index) => {
        const start = (this.currentPage-1) * this.pageSize;
        const end = this.currentPage * this.pageSize;

        if (index >= start && index < end) return true;
      })
      .filter((row: User) => {
        const name = row.name.toString().toLowerCase();
        const account = row.account.toString().toLowerCase();
        const status = row.status.toString().toLowerCase();
        const searchTerm = this.filter.toLowerCase();

        return name.includes(searchTerm) ||
          account.includes(searchTerm) || status.includes(searchTerm);
      });
    }
  },
  
  data() {
    return {
      users:[],
      pageSize:5,
      currentPage:1,
      filter:'',
    }
  }
});
</script>
