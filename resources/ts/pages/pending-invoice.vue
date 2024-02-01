<script setup>
import {ref,watch} from "vue";
import axios from "@axios";
import {debounce} from 'lodash'
import Papa from 'papaparse'
import { saveAs } from 'file-saver';


const overlay = ref(false)
const searchQuery = ref('')
const selectedStatus = ref()
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalInvoices = ref(0)
const invoices = ref([])
const selectedRows = ref([])
const selectAllInvoice = ref(false)
const showStatus = ref(false)
const statusDetails = ref("")
const addModal = ref(false)
const updateModal = ref(false)
const errors = ref([])
const error = ref([])
const firstIndex = ref(0)
const selectAction = ref("")
const page = ref(1)
const links = ref(0)
const lastIndex = ref(0)
const exportData = ref("")
const search = ref()
const loadPermissions = ref(false)
const status = ref("pending")
const with_invoice = ref("")
const to = ref("")
const from = ref("")
const permissions = ref(["approved","rejected"])
const form = ref({
  item_code:'',
  item_name:'',
  item_price:'',
  item_description:'',
  proforma_invoice:'',
  branch:'',
})
const table = ref(null)
const ed_form = ref({
  status:'',
  comments:'',
  id:''
})
const processing = ref(false)
const ed_processing = ref(false)
const addData = async () => {
  processing.value = true
  try {
    await axios.post(`/api/user/send-invoice`,form.value,{
      headers: {
        "Content-Type":"multipart/form-data"
      }
    })
    form.value.name = ""
    form.value.email = ""
    form.value.password = ""
    form.value.password_confirmation = ""
    form.value.role = []
    processing.value = false
    addModal.value = false
    showStatus.value = true
    statusDetails.value = "Employee added Successfully"
    await getData()
  }catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }
    processing.value = false
  }
}
const clearErrors = (val) => {
  delete errors.value[val]
}
const clearError = (val) => {
  delete error.value[val]
}

const getImage = (e) => {
  form.value.proforma_invoice = e.target.files[0]
}

const getData = async () => {
  const params = {
    q: searchQuery.value.toUpperCase(),
    status: status.value,
    to: to.value,
    from: from.value,
    with_invoice: with_invoice.value,
    perPage: rowPerPage.value,
    page:currentPage.value,
    currentPage: currentPage.value,
  }
  try {
    overlay.value = true
    let response = await axios.get('/api/user/all-invoice', { params })
    overlay.value = false
    invoices.value = response.data.data
    totalPage.value = response.data.total
    totalInvoices.value = response.data.total
    currentPage.value = response.data.current_page
    firstIndex.value = response.data.from
    lastIndex.value = response.data.last_page
    links.value = response.data.links.length
  }catch (e) {
    console.log(e.response)
    overlay.value = false
  }
}

watch(() => currentPage.value,async () => {
  await getData()
})
watch(() => rowPerPage.value,async () => {
  await getData()
})

const showData = async (id) => {
  try {
    overlay.value = true
    let response = await axios.get(`/api/user/get-invoice/${id}`)
    ed_form.value.status = response.data.status
    ed_form.value.comments = response.data.comments
    ed_form.value.id = response.data.id
    updateModal.value = true
    console.log(response)
    overlay.value = false
  }catch (e){
    overlay.value = false
    console.log(e.response)
  }
}

const updateData = async () => {
  try {
    await axios.patch(`/api/user/update-invoice/${ed_form.value.id}`,ed_form.value)
    ed_form.value.name = ""
    ed_form.value.id = ""
    ed_form.value.permissions = []
    updateModal.value = false
    showStatus.value = true
    statusDetails.value = "Employee updated Successfully"
    await getData()
  }catch (e) {
    console.log(e.response)
    if (e.response.status === 422) {
      error.value = e.response.data.errors
    }
  }
}
watch(() => selectAction.value, async () => {
  if(selectAction.value === "Delete") {
    if (window.confirm("Are you sure?")) {
      await deleteDate()
    }
  }
})
const deleteDate = async () => {
  try {
    await axios.post(`/api/user/employee/delete`,{id:selectedRows.value})
    showStatus.value = true
    statusDetails.value = "Role Deleted Successfully"
    await getData()
    selectAction.value = ""
  }catch (e) {
    selectAction.value = ""
  }
}


// 👉 Add/Remove all checkbox ids in/from array
const selectUnselectAll = () => {
  selectAllInvoice.value = !selectAllInvoice.value
  if (selectAllInvoice.value) {
    invoices.value.forEach(invoice => {
      if (!selectedRows.value.includes(`${ invoice.id }`))
        selectedRows.value.push(`${ invoice.id }`)
    })
  } else {
    selectedRows.value = []
  }
}
const columns = [
  {title: "ID", dataKey: "id"},
  {title: "Name", dataKey: "name"},
  {title: "Permissions", dataKey: "permissions"},
  {title: "Users", dataKey: "users"},
];


// 👉 watch if checkbox array is empty all checkbox should be unchecked
watch(selectedRows, () => {
  if (!selectedRows.value.length)
    selectAllInvoice.value = false
}, { deep: true })

const addRemoveIndividualCheckbox = checkID => {
  if (selectedRows.value.includes(checkID)) {
    const index = selectedRows.value.indexOf(checkID)

    selectedRows.value.splice(index, 1)
  } else {
    selectedRows.value.push(checkID)
    selectAllInvoice.value = true
  }
}
watch(search,() => {
  getPermissions()
})
const getPermissions = debounce(async ()=> {
  if (search.value === "") return
  loadPermissions.value = true
  try {
    let response = await axios.get(`/api/user/role/name/${search.value}`)
    console.log(response)
    permissions.value = response.data
    loadPermissions.value = false
  }catch (e) {
    loadPermissions.value = false
  }
},1000)

const ch =async (stat) => {
  status.value = stat
  await getData()
}

const downLoadExcel = async () => {
  const params = {
    q: searchQuery.value.toUpperCase(),
    status: status.value,
    to: to.value,
    from: from.value,
    with_invoice: with_invoice.value,
    perPage: rowPerPage.value,
    page:currentPage.value,
    currentPage: currentPage.value,
  }
  try {
    overlay.value = true
    let response = await axios.get('/api/user/download-invoice', { params })
    overlay.value = false
    console.log(response.data)
    const csv = Papa.unparse(response.data,{escapeChar: '"'});
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    saveAs(blob, 'tableData.csv');
  }catch (e) {
    console.log(e.response)
    overlay.value = false
  }
}

onMounted(async () => {
  await getData()
})
</script>

<template>
  <div>

    <section v-if="invoices">
      <!-- 👉 Invoice Filters  -->
      <VCardText class="d-flex flex-column gap-4">
      <div v-if="$can('list','invoice')">
        <VTabs>
          <VTab @click.prevent="ch('pending')">Inbox</VTab>
          <VTab @click.prevent="ch('ch')">OutBox</VTab>
          <VTab @click.prevent="ch('')">All</VTab>
        </VTabs>
      </div>
      </VCardText>

      <VCard id="invoice-list">
        <VCardText class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Actions  -->
          <div class="me-3" v-if="$can('delete','invoice')">
            <VSelect
              density="compact"
              label="Actions"
              :items="['Delete']"
              v-model="selectAction"
              class="invoice-list-actions"
              :disabled="!selectedRows.length"
            />
          </div>

          <VSpacer />

          <div class="d-flex align-center flex-wrap gap-4">
            <VBtn v-if="$can('list','employee')"
                  @click.prevent="downLoadExcel"
            >
              EXCEL
            </VBtn>
            <div class="invoice-list-search" v-if="$can('update','processing')" >
              <VTextField
                type="date"
                label="From"
                v-model="from"
                placeholder="Search"
                density="compact"
              />
            </div>
            <div class="invoice-list-search" v-if="$can('update','processing')" >
              <VTextField
                label="To"
                type="date"
                v-model="to"
                placeholder="Search"
                density="compact"
              />
            </div>
            <!-- 👉 Search  -->
            <div class="me-3" v-if="$can('update','processing')">
              <VSelect
                clearable
                density="compact"
                label="Invoice"
                :items="['with invoice','without invoice']"
                class="invoice-list-actions"
                v-model="with_invoice"
              />
            </div>
            <div class="me-3" v-if="$can('update','processing')">
              <VSelect
                clearable
                density="compact"
                label="Status"
                :items="['pending','rejected','approved']"
                class="invoice-list-actions"
                v-model="status"
              />
            </div>
            <div class="invoice-list-search" v-if="$can('list','invoice')" >
              <VTextField
                v-model="searchQuery"
                placeholder="Search"
                density="compact"
              />
            </div>

            <!-- 👉 Create invoice -->
            <VBtn v-if="$can('list','invoice')"
                  prepend-icon="mdi-search"
                  @click.prevent="getData"
            >
              Search
            </VBtn>
<!--            <VBtn v-if="$can('add','employee')"-->
<!--                  @click.prevent="addModal = true"-->
<!--                  prepend-icon="mdi-plus"-->
<!--            >-->
<!--              Create Request-->
<!--            </VBtn>-->
          </div>
        </VCardText>

        <VDivider />

        <!-- SECTION Table -->
        <VTable ref="table" class="text-no-wrap table-header-bg rounded-0">
          <!-- 👉 Table head -->
          <thead>
          <tr>
            <!-- 👉 Check/Uncheck all checkbox -->
            <th
              scope="col"
              class="text-center"
            >
              <div style="width: 1rem;">
                <VCheckbox
                  :model-value="selectAllInvoice"
                  :indeterminate="(invoices.length !== selectedRows.length) && !!selectedRows.length"
                  @click="selectUnselectAll"
                />
              </div>
            </th>
            <th scope="col">
              #ID
            </th>
            <th scope="col">
              EMPLOYEE
            </th>
            <th scope="col">
              INITIAL AMOUNT
            </th>
            <th scope="col">
              DISCOUNTED AMOUNT
            </th>
            <th scope="col">
              DATE
            </th>
            <th scope="col">
              PROFORMA
            </th>
            <th scope="col">
              SUBMITTED DATA
            </th>
            <th scope="col">
              VOUCHER
            </th>
            <th scope="col" >
              STATUS
            </th>
            <th scope="col" >
              ACTIONS
            </th>
          </tr>
          </thead>
          <!-- 👉 Table Body -->
          <tbody>
          <tr
            v-for="invoice in invoices"
            :key="invoice.id"
            :style="invoice.status === 'rejected' ? {color:'red'} : invoice.status === 'pending' ? {color:'#FFB714'} : invoice.status === 'approved' ? {color: 'green'} : ''"
          >
            <!-- 👉 Individual checkbox -->
            <td>
              <div style="width: 1rem;">
                <VCheckbox
                  :id="`${invoice.id}`"
                  :model-value="selectedRows.includes(`${invoice.id}`)"
                  @click="addRemoveIndividualCheckbox(`${invoice.id}`)"
                />
              </div>
            </td>

            <!-- 👉 Id -->
            <td>

              #{{ invoice.id }}
            </td>

            <!-- 👉 Trending -->


            <!-- 👉 Client Avatar and Email -->

            <!-- 👉 total -->
            <td class="">
              <div >
                {{invoice.user.name}}
              </div>
            </td>
            <td class="">
              <div >
               GHC {{invoice.total}}
              </div>
            </td>
            <td class="">
              <div >
                {{ Number(invoice.discounted_amount).toLocaleString('en-US', { style: 'currency', currency: 'GHC' }) }}
              </div>
            </td>
            <td class="">
              <div >
                {{ invoice.date }}
              </div>
            </td>

            <td class="">
              <div class="">
                <a class="d-block" v-for="proforma in invoice.proforma" target="_blank" :href="proforma.proforma">{{proforma.name}}</a>
              </div>
            </td>
            <td class="">
              <router-link :to="{name:'view-invoice',query:{id:invoice.id}}">view</router-link>
            </td>
            <td class="">
              <div class="" v-if="invoice.invoice.length">
                <a target="_blank" :href="invoice.invoice[0].voucher">VOUCHER</a>
                <br>
                <a target="_blank" :href="invoice.invoice[0].delivery_note">DELIVERY NOTE</a>
              </div>
            </td>
            <td class="">
                {{invoice.status.toUpperCase()}}
            </td>

            <!-- 👉 Actions -->
            <td style="width: 8rem;" >

<!--                           <IconBtn v-if="$can('update','processing')" @click.prevent="deleteDate(invoice.id)" >-->
<!--                             <VIcon icon="mdi-eye-outline" />-->
<!--                           </IconBtn>-->

                            <IconBtn v-if="$can('update','processing')" @click.prevent="showData(invoice.id)" >
                              <VIcon icon="mdi-edit-outline" />
                            </IconBtn>
            </td>
          </tr>
          </tbody>

          <!-- 👉 table footer  -->
          <tfoot v-show="!invoices.length">
          <tr>
            <td
              colspan="8"
              class="text-center text-body-1"
            >
              No data available
            </td>
          </tr>
          </tfoot>
        </VTable>
        <!-- !SECTION -->

        <VDivider />

        <!-- SECTION Pagination -->
        <VCardText class="d-flex flex-wrap justify-end gap-4 pa-2">
          <!-- 👉 Rows per page -->
          <div
            class="d-flex align-center me-3"
            style="width: 171px;"
          >
            <span class="text-no-wrap me-3">Rows per page:</span>
            <VSelect
              v-model="rowPerPage"
              density="compact"
              variant="plain"
              class="mt-n4"
              :items="[5,10, 20, 30, 50,100,200,500,1000,5000]"
            />
          </div>

          <!-- 👉 Pagination and pagination meta -->
          <div class="d-flex align-center">
            <h6 class="text-sm font-weight-regular">
              {{ `${ firstIndex }-${ lastIndex } of ${ totalInvoices }` }}
            </h6>

            <VPagination
              v-model="currentPage"
              size="small"
              :total-visible="1"
              :length="totalPage"
              @next="selectedRows = []"
              @prev="selectedRows = []"
            />
          </div>
        </VCardText>
        <!-- !SECTION -->
      </VCard>
    </section>
    <!--   add data-->
    <VDialog
      v-model="addModal"
      persistent
      max-width="600"
    >
      <!-- Dialog Content -->
      <VCard title="Create Request">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="addModal = false"
        />

        <VCardText>
          <VRow>
            <VCol
              cols="12"
            >
              <VTextField

                v-model="form.item_code"
                :readonly="processing"
                label="Item Code"
                @input="clearErrors('item_code')"
                :class="{'v-field--error': errors?.item_code}"
              />
              <small style="color: #ff4c20" v-if="errors.item_code">{{errors.item_code[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField

                v-model="form.item_name"
                :readonly="processing"
                label="Item Name"
                @input="clearErrors('item_name')"
                :class="{'v-field--error': errors?.item_name}"
              />
              <small style="color: #ff4c20" v-if="errors.item_name">{{errors.item_name[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                type="number"
                v-model="form.item_price"
                :readonly="processing"
                label="Item Price"
                @input="clearErrors('item_price')"
                :class="{'v-field--error': errors?.item_price}"
              />
              <small style="color: #ff4c20" v-if="errors.item_price">{{errors.item_price[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                v-model="form.branch"
                :readonly="processing"
                label="Shop"
                @input="clearErrors('branch')"
                :class="{'v-field--error': errors?.branch}"
              />
              <small style="color: #ff4c20" v-if="errors.branch">{{errors.branch[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                @change="getImage"
                type="file"
                :readonly="processing"
                label="Invoice"
                @input="clearErrors('proforma_invoice')"
                :class="{'v-field--error': errors?.proforma_invoice}"
              />
              <small style="color: #ff4c20" v-if="errors.proforma_invoice">{{errors.proforma_invoice[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextarea
                v-model="form.item_description"
                :readonly="processing"
                label="Item Decription"
                @input="clearErrors('item_description')"
                :class="{'v-field--error': errors?.item_description}"
              />
              <small style="color: #ff4c20" v-if="errors.item_description">{{errors.item_description[0]}}</small>
            </VCol>
          </VRow>
        </VCardText>

        <VCardActions>
          <VSpacer />
          <VBtn
            color="error"
            @click="addModal = false"
          >
            Close
          </VBtn>
          <VBtn
            @click.prevent="addData"
            :disabled="processing"
            :loading="processing"
            color="success"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!--   end data-->
    <!--   edit data-->
    <VDialog
      v-model="updateModal"
      persistent
      max-width="600"
    >
      <!-- Dialog Content -->
      <VCard title="Update Request">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="updateModal = false"
        />

        <VCardText>
          <VRow>
            <VCol cols="12">
              <VAutocomplete
                v-model="ed_form.status"
                :readonly="processing"
                label="Status"
                :items="permissions"
                @input="clearError('status')"
                :class="{'v-field--error': error?.status}"
              />
              <small style="color: #ff4c20" v-if="error.status">{{error.status[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextarea

                v-model="ed_form.comments"
                :readonly="processing"
                label="Comment"
                @input="clearError('comments')"
                :class="{'v-field--error': error?.comments}"
              />
              <small style="color: #ff4c20" v-if="error.comments">{{error.comments[0]}}</small>
            </VCol>
          </VRow>
        </VCardText>

        <VCardActions>
          <VSpacer />
          <VBtn
            color="error"
            @click="updateModal = false"
          >
            Close
          </VBtn>
          <VBtn
            @click.prevent="updateData"
            :disabled="ed_processing"
            :loading="ed_processing"
            color="success"
          >
            update
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!--   edit data-->
    <VSnackbar
      v-model="showStatus"
      location="top left"
      variant="flat"
      color="success"
    >
      {{ statusDetails }}
    </VSnackbar>
    <v-overlay
      persistent
      :model-value="overlay"
      class="align-center justify-center"
    >
      <v-progress-circular
        color="primary"
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>
  </div>
</template>

<style lang="scss">
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-search {
    inline-size: 12rem;
  }
}
</style>
<route lang="yaml">
  meta:
    action: update
    subject: pending
</route>
