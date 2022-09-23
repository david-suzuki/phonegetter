<template>
  <!--begin::Tables Widget 9-->
  <div class="card" :class="widgetClasses">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bolder fs-3 mb-1">Calling Numbers</span>
      </h3>
      <div class="d-flex align-items-center position-relative me-4" style="display: flex; justify-content: flex-end">
        <input
          type="text"
          id="kt_filter_search"
          class="form-control form-control-white form-control-sm w-150px ps-9"
          placeholder="Search"
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
              <th class="min-w-150px">Phone Number</th>
              <th class="min-w-150px">Client Name</th>
              <th class="min-w-60px">Select</th>
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
                  <div
                    class="form-check form-check-sm form-check-custom form-check-solid"
                  >
                    <input
                      class="form-check-input widget-9-check"
                      type="checkbox"
                      :value="index"
                      @click="checkNumber(phone.id, $event)"
                    />
                  </div>
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
      <a href="#" class="btn btn-danger" @click="callPhone">Call</a>
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
</template>

<script lang="ts">

import { defineComponent, ref } from "vue";
import axios from 'axios';
import Swal from "sweetalert2/dist/sweetalert2.js"; 

interface Phone {
  pnumber: string;
  cname: string;
}

export default defineComponent({
  name: "CallingTable",
  components: {},
  props: {
    widgetClasses: String
  },
  mounted(){
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    axios
    .get("http://localhost:8000/call/list", { headers: authHeader })
    .then((response)=>
    { 
      this.phones = response.data;
    }).catch((err) => {
      console.log(err);
    });
  },

  methods: {
    checkNumber(id:never, event) {
      if (event.target.checked)
        this.checkedNumbers.push(id);
      else {
        const index = this.checkedNumbers.indexOf(id);
        if (index !== -1) {
          this.checkedNumbers.splice(index, 1);
        }
      }
    },

    callPhone() {
      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };

      const data = {numbers: this.checkedNumbers};

      axios
      .post("http://localhost:8000/call/proc", data, { headers: authHeader })
      .then((response)=>
      { 
        console.log(response);
      }).catch((err) => {
        console.log(err);
      });
    },

    nextPage:function() {
      if ((this.currentPage*this.pageSize) < this.phones.length) this.currentPage++;
    },

    prevPage:function() {
      if (this.currentPage > 1) this.currentPage--;

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
          cname.includes(searchTerm) || status.includes(searchTerm);
      });
    }
  },
  
  data() {
    return {
      phones: [],
      checkedNumbers: [],
      pageSize:5,
      currentPage:1,
      filter:'',
    }
  }
});
</script>
