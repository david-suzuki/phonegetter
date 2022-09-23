<template>

  <div class="row gy-5 gx-xl-8">

    <!--begin::Tables Widget 9-->
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
      <!--begin::Header-->
      <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bolder fs-3 mb-1">{{ translate('phonesReport') }}</span>
        </h3>
      </div>
      <!--end::Header-->

      <div class="row g-6 g-xl-9 mb-4 mb-xl-6" style="margin-left: 10px">
        <div class="col-lg-3">
          <el-date-picker
            v-model="startDT"
            type="datetime"
            :placeholder="translate('startDateTime')">
          </el-date-picker> 
        </div>

        <div class="col-lg-3">
          <el-date-picker
            v-model="endDT"
            type="datetime"
            :placeholder="translate('endDateTime')">
          </el-date-picker>
        </div>

        <div class="col-lg-3">
          <el-select
            class="form-select-solid"
            placeholder="Tag"
            v-model="selectedTag"
          >
            <el-option v-for="tag in tags" :key="tag.id" :label="tag.tname" :value="tag.id"></el-option>
          </el-select>
        </div>

        <div class="col-lg-2">
          <el-select
            class="form-select-solid"
            :placeholder="translate('status')"
            v-model="selectedStatus"
          >
            <el-option value="normal" label="normal"></el-option>
            <el-option value="locked" label="locked"></el-option>
          </el-select>
        </div>
      </div>

      <div class="row g-6 g-xl-9 mb-4 mb-xl-6">
        <div class="col-lg-3">
          <div class="fs-6 fw-bold text-gray-700">
            <!--begin::Button-->
            <div class="d-flex justify-content-start px-9">
              <button
                type="button"
                id="searchButton"
                class="btn btn-primary"
                @click = "search"
              >
                <span class="indicator-label">
                  {{ translate('search') }}
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
          </div>
        </div>
      </div>

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
                <th class="min-w-50px">No</th>
                <th class="min-w-120px">{{ translate('phoneNumber') }}</th>
                <th class="min-w-120px">{{ translate('clientName') }}</th>
                <th class="min-w-100px">{{ translate('status') }}</th>
                <th class="min-w-120px">{{ translate('tagName') }}</th>
                <th class="min-w-120px">{{ translate('dateTime') }}</th>
              </tr>
            </thead>
            <!--end::Table head-->

            <!--begin::Table body-->
            <tbody>
              <template v-for="(history, index) in pagedRows" :key="history.id">
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
                          >{{ history.pnumber }}</a
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
                          >{{ history.cname }}</a
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
                          >{{ history.status }}</a
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
                          >{{ history.tname }}</a
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
                          >{{ history.created }}</a
                        >
                      </div>
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
    <div class="d-flex flex-stack flex-wrap" style="margin-top:1px !important">
      <div class="fs-6 fw-bold text-gray-700">
        <!--begin::Button-->
        <div class="d-flex justify-content-start px-9">
          <button
            type="button"
            id="excelButton"
            class="btn btn-success"
            @click = "toExcel"
          >
            <span class="indicator-label">
              Excel
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
import { useI18n } from "vue-i18n";

interface History {
  pnumber: string;
  cname: string;
  status: string;
  tname: string;
  created: string;
}

export default defineComponent({
  name: "report",
  components: {
    // Datepicker,
    // VueTimepicker
  },

  setup() {
    const { t, te } = useI18n();
    onMounted(() => {
      setCurrentPageBreadcrumbs("report", []);
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      translate
    };
  },

  mounted(){

    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    axios
    .get("http://localhost:8000/report/list", { headers: authHeader })
    .then((response)=>
    { 
      this.histories = response.data.history;
      this.tags = response.data.tag;
    }).catch((err) => {
      console.log(err);
    });
  },

  methods: {

    nextPage:function() {
      if ((this.currentPage*this.pageSize) < this.histories.length) this.currentPage++;
    },

    prevPage:function() {
      if (this.currentPage > 1) this.currentPage--;
    },

    search() {
      // console.log(this.startDT, this.endDT, this.selectedTag, this.selectedStatus);
      // const sdt = new Date(this.startDT).toUTCString();
      const searchButton = document.getElementById('searchButton')!;
      searchButton.setAttribute("data-kt-indicator", "on");

      const data = {
        start: this.startDT.toString(),
        end: this.endDT.toString(),
        tag: this.selectedTag,
        status: this.selectedStatus
      }

      const token = localStorage.getItem('id_token');
      const authHeader = { Authorization: 'Bearer ' + token };

      axios
      .post("http://localhost:8000/report/search", data, { headers: authHeader })
      .then((response)=>
      { 
        this.histories = response.data;
        searchButton.removeAttribute("data-kt-indicator");
      }).catch((err) => {
        Swal.fire({
          text: "Searching fail.",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Error!",
          customClass: {
            confirmButton: "btn fw-bold btn-light-danger"
          }
        });
        searchButton.removeAttribute("data-kt-indicator");
      });
    },

    toExcel() {
      const data = 
        "start="+this.startDT.toString()+
        "&end="+this.endDT.toString()+
        "&tag="+this.selectedTag+
        "&status="+this.selectedStatus;
      

      const token = localStorage.getItem('id_token');
      const headers = { 
        Authorization: 'Bearer ' + token,
        "Content-Type": "application/x-www-form-urlencoded"
      };

      // const formData = new FormData();
      // formData.append("start", this.startDT.toString());
      // formData.append("end", this.endDT.toString());
      // formData.append("tag", this.selectedTag);
      // formData.append("status", this.selectedStatus);

      axios
      .post("http://localhost:8000/report/excel", data, { headers: headers, responseType: 'arraybuffer' })
      .then(response => {
        const fileURL = window.URL.createObjectURL(new Blob([response.data]));
        const fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute('download', 'report.xlsx');
        document.body.appendChild(fileLink);
        fileLink.click();
      }).catch((err) => {
        Swal.fire({
          text: "Excel exporting fail.",
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
    pagedRows():History[] {
      return this.histories.filter((row, index) => {
        const start = (this.currentPage-1) * this.pageSize;
        const end = this.currentPage * this.pageSize;

        if (index >= start && index < end) return true;
      });
      // .filter((row: History) => {
      //   const pnumber = row.pnumber.toString().toLowerCase();
      //   const cname = row.cname.toString().toLowerCase();
      //   const status = row.status.toString().toLowerCase();
      //   const tname = row.tname.toString().toLowerCase();
      //   const created = row.created.toString().toLowerCase();
      //   const searchTerm = this.filter.toLowerCase();

      //   return pnumber.includes(searchTerm) ||
      //     cname.includes(searchTerm) || 
      //     status.includes(searchTerm) ||
      //     tname.includes(searchTerm) ||
      //     created.includes(searchTerm);
      // });
    }
  },
  
  data() {
    return {
      histories: [],
      tags: [],
      pageSize:5,
      currentPage:1,
      filter:'',
      selectedStatus: '',
      selectedTag: '',
      startDT: '',
      endDT: '',
    }
  }
});
</script>
