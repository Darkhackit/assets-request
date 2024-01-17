<script setup>
import {ref,watch} from "vue";
import axios from "@axios";
import {debounce} from 'lodash'


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
const editModal = ref(false)
const errors = ref([])
const updateErrors = ref([])
const error = ref([])
const firstIndex = ref(0)
const selectAction = ref("")
const page = ref(1)
const links = ref(0)
const lastIndex = ref(0)
const exportData = ref("")
const search = ref()
const loadPermissions = ref(false)
const permissions = ref([])
const form = ref({
  vendor:'',
  status:'',
  branch:'',
  phone_number:'',
  proforma:[
    {
      name:'',
      proforma:''
    }
  ],
  items:[{
    name:'',
    code:'',
    price:0,
    quantity:0
  }]
})
const updatedForm = ref({
  vendor:'',
  status:'',
  phone_number:'',
  branch:'',
  proforma:[],
  items:[],
  id:''
})
const table = ref(null)
const ed_form = ref({
  voucher:'',
  discounted_amount:0,
  delivery_note:'',
  id:''
})
const processing = ref(false)
const ed_processing = ref(false)
const addData = async (status) => {
  form.value.status = status
  processing.value = true
  try {
    await axios.post(`/api/user/send-invoice`,form.value,{
      headers: {
        "Content-Type":"multipart/form-data"
      }
    })
    form.value.status = ""
    form.value.vendor = ""
    form.value.branch = ""
    form.value.phone_number = ""
    form.value.items = []
    form.value.proforma = []
    processing.value = false
    addModal.value = false
    showStatus.value = true
    statusDetails.value = "Request added Successfully"
    await getData()
  }catch (e) {
    console.log(e)
    if (e.response.status === 422) {
      processing.value = false
      return errors.value = e.response.data.errors
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
const clearUpdatedError = (val) => {
  delete updateErrors.value[val]
}

const getImage = (e) => {
  form.value.proforma_invoice = e.target.files[0]
}

const getDoc = (e,i) => {
  let p = form.value.proforma.find((v,index) => index === i )
  p.proforma = e.target.files[0]
  console.log(p)
}

const getEditedDoc = (e,i) => {
  let p = updatedForm.value.proforma.find((v,index) => index === i )
  p.proforma = e.target.files[0]
  console.log(p)
}

const show = async (id) => {
  try {
    overlay.value = true
    let response = await axios.get(`/api/user/get-invoice/${id}`)
    if(response.data.status !== 'draft') {
      overlay.value = false
      return alert(`Request is not in   ${response.data.status} mode`)
    }
    updatedForm.value.id = response.data.id
    updatedForm.value.vendor = response.data.vendor
    updatedForm.value.branch = response.data.branch
    updatedForm.value.proforma = response.data.proforma
    updatedForm.value.items = response.data.items
    updatedForm.value.phone_number = response.data.phone_number
    editModal.value = true
    overlay.value = false
  }catch (e){
    overlay.value = false
    console.log(e.response)
  }
}

const getData = async () => {
  const params = {
    q: searchQuery.value.toUpperCase(),
    status: selectedStatus.value,
    perPage: rowPerPage.value,
    page:currentPage.value,
    currentPage: currentPage.value,
  }
  try {
    overlay.value = true
    let response = await axios.get('/api/user/send-invoice', { params })
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

const addItems = () => {
  form.value.items.push({
    name:'',
    code:'',
    price:0,
    quantity:0
  })
}
const addEditedItems = () => {
  updatedForm.value.items.push({
    name:'',
    code:'',
    price:0,
    quantity:0
  })
}

const addDocuments = () => {
  form.value.proforma.push({
    name:'',
    proforma:'',
  })
}
const addEditedDocuments = () => {
  updatedForm.value.proforma.push({
    name:'',
    proforma:'',
  })
}

const deleteItem = (id) => {
  if (form.value.items.length <= 1) return
  form.value.items.splice(id,1)
}
const deleteEditedItem = (id) => {
  if (updatedForm.value.items.length <= 1) return
  updatedForm.value.items.splice(id,1)
}
const deleteDocuments = (id) => {
  if (form.value.proforma.length <= 1) return
  form.value.proforma.splice(id,1)
}
const deleteEditedDocuments = (id) => {
  if (updatedForm.value.proforma.length <= 1) return
  updatedForm.value.proforma.splice(id,1)
}


const showData = async (id) => {
  try {
    overlay.value = true
    let response = await axios.get(`/api/user/get-invoice/${id}`)
    ed_form.value.id = response.data.id
    if(response.data.status !== 'approved') {
      overlay.value = false
      return alert(`Requested is ${response.data.status}`)
    }
    updateModal.value = true
    overlay.value = false
  }catch (e){
    overlay.value = false
    console.log(e.response)
  }
}

const updateSavedData = async (s) => {
  updatedForm.value.status = s
  try {
    await axios.post(`/api/user/update-data/${updatedForm.value.id}`,updatedForm.value,{
      headers: {
        "Content-Type":"multipart/form-data"
      }
    })
    ed_form.value.voucher = ""
    ed_form.value.id = ""
    ed_form.value.delivery_note = ""
    editModal.value = false
    showStatus.value = true
    statusDetails.value = "Document updated Successfully"
    await getData()
  }catch (e) {
    console.log(e.response)
    if (e.response.status === 422) {
      updateErrors.value = e.response.data.errors
    }
  }
}

const updateData = async () => {
  try {
    await axios.post(`/api/user/update-document/${ed_form.value.id}`,ed_form.value,{
      headers: {
        "Content-Type":"multipart/form-data"
      }
    })
    ed_form.value.voucher = ""
    ed_form.value.id = ""
    ed_form.value.delivery_note = ""
    updateModal.value = false
    showStatus.value = true
    statusDetails.value = "Document updated Successfully"
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

const deleteInvoiceDate = async () => {
  try {
    await axios.post(`/api/user/invoice/delete`,{id:selectedRows.value})
    showStatus.value = true
    statusDetails.value = "Request Deleted Successfully"
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

const uploadDocuments = (status) => {
  if(status !== 'approved') {
    return alert(`Requested is ${status}`)
  }
  updateModal.value = true
}

const getVoucher = (e) => {
    ed_form.value.voucher = e.target.files[0]
}
const getDeliveryNote = (e) => {
  ed_form.value.delivery_note = e.target.files[0]
}


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
  loadPermissions.value = true
  try {
    let response = await axios.post(`/api/user/vendors/name`)
    console.log(response)
    permissions.value = response.data
    loadPermissions.value = false
  }catch (e) {
    loadPermissions.value = false
  }
},1000)

onMounted(async () => {
  await getData()
  await getPermissions()
})
</script>

<template>
  <div>
    <section v-if="invoices">
      <!-- 👉 Invoice Filters  -->

      <VCard id="invoice-list">
        <VCardText class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Actions  -->
          <div class="me-3" v-if="$can('delete','employee')">
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
            <!-- 👉 Search  -->
            <div class="invoice-list-search" v-if="$can('list','invoice')">
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
            <VBtn v-if="$can('add','invoice')"
                  @click.prevent="addModal = true"
                  prepend-icon="mdi-plus"
            >
              Create Request
            </VBtn>
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

            <th
              scope="col"
            >
              ITEM TOTAL
            </th>
            <th
              scope="col"
            >
              VENDOR
            </th>
            <th scope="col">
              BRANCH
            </th>
            <th scope="col">
              DATE
            </th>
            <th scope="col">
              STATUS
            </th>
            <th scope="col" >
              SUBMITTED DATA
            </th>
            <th scope="col" >
              PROFORMA INVOICE
            </th>
            <th scope="col" >
              Final Invoice
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
            style="color: "
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

            <!-- 👉 total -->
            <td class="">
              <div >
               GHC {{invoice.total}}
              </div>
            </td>
            <td class="">
              <div >
                {{invoice.vendor}}
              </div>
            </td>
            <td class="">
              <div >
                {{invoice.shop}}
              </div>
            </td>
            <td class="">
                {{invoice.date}}
            </td>
            <td class="">
              {{invoice.status.toUpperCase()}}
            </td>
            <td class="">
             <router-link :to="{name:'view-invoice',query:{id:invoice.id}}">view</router-link>
            </td>
            <td class="">
              <div class="">
                <a class="d-block" v-for="proforma in invoice.proforma" target="_blank" :href="proforma.proforma">{{proforma.name}}</a>
              </div>
            </td>


            <!-- 👉 Actions -->
            <td style="width: 8rem;" >

              <!--             <IconBtn @click.prevent="deleteDate(invoice.id)" >-->
              <!--               <VIcon icon="mdi-delete-outline" />-->
              <!--             </IconBtn>-->

<!--              <IconBtn @click.prevent="showData(invoice.id)" >-->
<!--                <VIcon icon="mdi-edit-outline" />-->
<!--              </IconBtn>-->

                <div class="" v-if="invoice.invoice.length">
                  <a target="_blank" :href="invoice.invoice[0].voucher">VOUCHER</a>
                  <br>
                  <a target="_blank" :href="invoice.invoice[0].delivery_note">DELIVERY NOTE</a>
                </div>
            </td>
            <td>
              <div v-if="invoice.status == 'draft'">
                <IconBtn @click.prevent="show(invoice.id)" >
                  <VIcon icon="mdi-edit-outline" />
                </IconBtn>
                <IconBtn @click.prevent="deleteInvoiceDate(invoice.id)" >
                  <VIcon icon="mdi-delete-outline" />
                </IconBtn>
              </div>
              <div v-else-if="invoice.status == 'rejected'">
                <IconBtn >
                  <VIcon icon="mdi-times" />
                </IconBtn>
              </div>
              <div v-else>
                <IconBtn >
                  <VIcon icon="mdi-check" />
                </IconBtn>
              </div>
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
      max-width="800"
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
              cols="6"
            >
              <VSelect
                density="compact"
                label="Vendor"
                @input="clearErrors('vendor')"
                :items="permissions"
                v-model="form.vendor"
                class="invoice-list-actions"
              />
              <small style="color: #ff4c20" v-if="errors.vendor">{{errors.vendor[0]}}</small>
            </VCol>
            <VCol
              cols="6"
            >
              <VTextField
                v-model="form.branch"
                :readonly="processing"
                label="Branch"
                @input="clearErrors('branch')"
                :class="{'v-field--error': errors?.branch}"
              />
              <small style="color: #ff4c20" v-if="errors.branch">{{errors.branch[0]}}</small>
            </VCol>
            <VCol
              cols="6"
            >
              <VTextField
                type="number"
                v-model="form.phone_number"
                :readonly="processing"
                label="Phone Number"
                @input="clearErrors('phone_number')"
                :class="{'v-field--error': errors?.phone_number}"
              />
              <small style="color: #ff4c20" v-if="errors.phone_number">{{errors.phone_number[0]}}</small>
            </VCol>
            <VCol cols="12">
              <VBtn @click.prevent="addDocuments" class="mb-2">Add Documents</VBtn>
              <VRow v-for="(pro,index) in form.proforma">
                <VCol cols="5">
                  <VTextField
                    v-model="pro.name"
                    :readonly="processing"
                    label="Proforma Invoice Name"
                    @input="clearErrors(`proforma.${index}.name`)"
                    :class="errors ? errors[`proforma.${index}.name`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol
                  cols="5"
                >
                  <VTextField
                    @change="getDoc($event,index)"
                    type="file"
                    :readonly="processing"
                    label="Proforma Invoice"
                    @input="clearErrors(`proforma.${index}.proforma`)"
                    :class="errors ? errors[`proforma.${index}.proforma`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="2">
                  <VBtn @click.prevent="deleteDocuments(index)">x</VBtn>
                </VCol>
              </VRow>
            </VCol>
           <VCol cols="12">
             <VBtn @click.prevent="addItems" class="mb-2">Add Items</VBtn>
             <VRow v-for="(item,index) in form.items">
               <VCol cols="3">
                 <VTextField
                   v-model="item.name"
                   :readonly="processing"
                   label="Item Description"
                   @input="clearErrors(`items.${index}.name`)"
                   :class="errors ? errors[`items.${index}.name`] ? 'v-field--error': '' : ''"
                 />
               </VCol>
               <VCol cols="2">
                 <VTextField
                   v-model="item.code"
                   :readonly="processing"
                   label="Item Code"
                   @input="clearErrors(`items.${index}.code`)"
                   :class="errors ? errors[`items.${index}.code`] ? 'v-field--error': '' : ''"
                 />
               </VCol>
               <VCol cols="3">
                 <VTextField
                   type="number"
                   v-model="item.price"
                   :readonly="processing"
                   label="Price"
                   @input="clearErrors(`items.${index}.price`)"
                   :class="errors ? errors[`items.${index}.price`] ? 'v-field--error': '' : ''"
                 />
               </VCol>
               <VCol cols="2">
                 <VTextField
                   type="number"
                   v-model="item.quantity"
                   :readonly="processing"
                   label="Quantity"
                   @input="clearErrors(`items.${index}.quantity`)"
                   :class="errors ? errors[`items.${index}.quantity`] ? 'v-field--error': '' : ''"
                 />
               </VCol>
               <VCol cols="2">
                 <VBtn @click.prevent="deleteItem(index)">&times;</VBtn>
               </VCol>
             </VRow>
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
            @click.prevent="addData('draft')"
            :disabled="processing"
            :loading="processing"
            color="success"
          >
            Save as Draft
          </VBtn>
          <VBtn
            @click.prevent="addData('pending')"
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
      <VCard title="Final Documents Uploads">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="updateModal = false"
        />

        <VCardText>
          <VRow>
            <VCol
              cols="12"
            >
              <VTextField
                type="number"
                v-model="ed_form.discounted_amount"
                :readonly="ed_processing"
                label="Discounted Amount"
                @input="clearError('discounted_amount')"
                :class="{'v-field--error': error?.discounted_amount}"
              />
              <small style="color: #ff4c20" v-if="error.discounted_amount">{{error.discounted_amount[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                type="file"
                @change="getVoucher"
                :readonly="ed_processing"
                label="Discount Voucher"
                @input="clearError('voucher')"
                :class="{'v-field--error': error?.voucher}"
              />
              <small style="color: #ff4c20" v-if="error.voucher">{{error.voucher[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                type="file"
                @change="getDeliveryNote"
                v-model="ed_form.delivery_note"
                :readonly="processing"
                label="Delivery Note"
                @input="clearError('delivery_note')"
                :class="{'v-field--error': error?.delivery_note}"
              />
              <small style="color: #ff4c20" v-if="error.delivery_note">{{error.delivery_note[0]}}</small>
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

    <!--   Update data-->
    <VDialog
      v-model="editModal"
      persistent
      max-width="800"
    >
      <!-- Dialog Content -->
      <VCard title="Create Request">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="editModal = false"
        />

        <VCardText>
          <VRow>
            <VCol
              cols="6"
            >
              <VSelect
                density="compact"
                label="Vendor"
                :items="permissions"
                v-model="updatedForm.vendor"
                class="invoice-list-actions"
              />
              <small style="color: #ff4c20" v-if="updateErrors.vendor">{{errors.vendor[0]}}</small>
            </VCol>
            <VCol
              cols="6"
            >
              <VTextField
                v-model="updatedForm.branch"
                :readonly="processing"
                label="Branch"
                @input="clearUpdatedError('branch')"
                :class="{'v-field--error': updateErrors?.branch}"
              />
              <small style="color: #ff4c20" v-if="updateErrors.branch">{{errors.updateErrors[0]}}</small>
            </VCol>
            <VCol
              cols="6"
            >
              <VTextField
                type="number"
                v-model="updatedForm.phone_number"
                :readonly="processing"
                label="Phone Number"
                @input="clearUpdatedError('phone_number')"
                :class="{'v-field--error': updateErrors?.phone_number}"
              />
              <small style="color: #ff4c20" v-if="updateErrors.phone_number">{{updateErrors.phone_number[0]}}</small>
            </VCol>
            <VCol cols="12">
              <VBtn @click.prevent="addEditedDocuments" class="mb-2">Add Documents</VBtn>
              <VRow v-for="(pro,index) in updatedForm.proforma">
                <VCol cols="5">
                  <VTextField
                    v-model="pro.name"
                    :readonly="processing"
                    label="Proforma Invoice Name"
                    @input="clearUpdatedError(`proforma.${index}.name`)"
                    :class="errors ? updateErrors[`proforma.${index}.name`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol
                  cols="5"
                >
                  <VTextField
                    @change="getEditedDoc($event,index)"
                    type="file"
                    :readonly="processing"
                    label="Proforma Invoice"
                    @input="clearUpdatedError(`proforma.${index}.proforma`)"
                    :class="errors ? updateErrors[`proforma.${index}.proforma`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="2">
                  <VBtn @click.prevent="deleteEditedDocuments(index)">x</VBtn>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VBtn @click.prevent="addEditedItems" class="mb-2">Add Items</VBtn>
              <VRow v-for="(item,index) in updatedForm.items">
                <VCol cols="3">
                  <VTextField
                    v-model="item.name"
                    :readonly="processing"
                    label="Item Description"
                    @input="clearUpdatedError(`items.${index}.name`)"
                    :class="errors ? updateErrors[`items.${index}.name`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="2">
                  <VTextField
                    v-model="item.code"
                    :readonly="processing"
                    label="Item Code"
                    @input="clearUpdatedError(`items.${index}.code`)"
                    :class="errors ? updateErrors[`items.${index}.code`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="3">
                  <VTextField
                    type="number"
                    v-model="item.price"
                    :readonly="processing"
                    label="Price"
                    @input="clearUpdatedError(`items.${index}.price`)"
                    :class="errors ? updateErrors[`items.${index}.price`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="2">
                  <VTextField
                    type="number"
                    v-model="item.quantity"
                    :readonly="processing"
                    label="Quantity"
                    @input="clearUpdatedError(`items.${index}.quantity`)"
                    :class="errors ? updateErrors[`items.${index}.quantity`] ? 'v-field--error': '' : ''"
                  />
                </VCol>
                <VCol cols="2">
                  <VBtn @click.prevent="deleteEditedItem(index)">x</VBtn>
                </VCol>
              </VRow>
            </VCol>
          </VRow>
        </VCardText>

        <VCardActions>
          <VSpacer />
          <VBtn
            color="error"
            @click="editModal = false"
          >
            Close
          </VBtn>
          <VBtn
            @click.prevent="updateSavedData('draft')"
            :disabled="processing"
            :loading="processing"
            color="success"
          >
            Save as Draft
          </VBtn>
          <VBtn
            @click.prevent="updateSavedData('pending')"
            :disabled="processing"
            :loading="processing"
            color="success"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!--   end Update-->

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
    action: list
    subject: invoice
</route>
