<template>
  <div class="p-4">

    <BasicTable @register="registerTable" @row-click="(row) => openModalLoading(row)">
      <template #toolbar>
        <a-button type="primary" @click="openModalLoading()"> 新增 </a-button>
      </template>
      <template #bodyCell="{ column, record, text }">
        <TableAction
          v-if="column.dataIndex === 'action'"
          :actions="[
            {
              label: '刪除',
              icon: 'clarity:trash-line',
              popConfirm: {
                title: '確定要刪除嗎',
                placement: 'left',
                confirm: handleActionChick.bind(null, $event, record, false),
              },
            },
          ]"
          stopButtonPropagation="true"
        />
      </template>
    </BasicTable>

    <Modal @register="register" @refresh-list="refreshTable" />
  </div>
</template>

<script lang="ts" setup>
  import { ref } from 'vue';

  import { BasicTable, useTable, TableAction } from '@/components/Table';

  import { PlaceHoderNameList, PlaceHoderNameDelete } from '@/api/PlaceHoderName';
  import { useModal } from '@/components/Modal';
  import Modal from './Modal.vue';

  const [register, { openModal }] = useModal();

  const columns = getBasicColumns();
  const filterFnData = ref({});
  const sortFnData = ref({});
  // 設定列表內容
  const [registerTable, { getPaginationRef, getForm, reload }] = useTable({
    title: 'PlaceHoderName List',
    titleHelpMessage: '點擊表格欄位名稱可顯示詳細內容',
    showIndexColumn: true,
    api: async () => {
      const { current, pageSize: items } = await getPaginationRef();
      console.log('current', current);
      console.log('items', items);

      // 取得搜尋表單資料
      const form = await getForm();
      console.log('form getForm:', form.getFieldsValue());
      // 設成陣列
      const likecols = [];
      // 用迴圈判斷 getFormConfig 設定的欄位是否有值如果有值就加入陣列
      console.log('form.getFieldsValue()', form.getFieldsValue());
      for (const key in form.getFieldsValue()) {
        if (form.getFieldsValue()[key]) {
          likecols.push({ [key]: form.getFieldsValue()[key] });
        }
      }
      console.log('likecols', likecols);
      const wherecols = [];

      const whereincols = [];
      console.log('filterFn', filterFnData.value);
      // 如果有 filterFnData.value 就加入 wherecols 陣列
      if (filterFnData.value) {
        whereincols.push(filterFnData.value);
      }
      console.log('whereincols', whereincols);

      // sortCols
      const sortcols = [];
      if (sortFnData.value && sortFnData.value.field) {
        console.log('sortFnData', sortFnData.value);
        if (sortFnData.value.order === 'ascend') {
          sortcols.push({
            [sortFnData.value.field]: 'asc',
          });
        } else {
          sortcols.push({
            [sortFnData.value.field]: 'desc',
          });
        }
      } else {
        sortcols.push({
          created_at: 'desc',
        });
      }

      const body = {
        page: current,
        items,
        wherecols,
        likecols,
        whereincols,
        sortcols,
      };

      console.log('body', body);
      const response = await PlaceHoderNameList(body);
      console.log('response', response);
      return {
        list: response.list,
        total: response.total,
      };
    },
    useSearchForm: true,
    // 設定篩選 filterFn
    filterFn: (data) => {
      filterFnData.value = data;
      return data;
    },
    // 設定排序
    sortFn: (data) => {
      console.log('sortFn', data);
      sortFnData.value = data;
    },

    columns: columns,
    showTableSetting: true,
    formConfig: getFormConfig(),
    bordered: true,
    pagination: { pageSize: 10, page: 1 },
    fetchSetting: {
      pageField: 'page',
      sizeField: 'items',
      listField: 'list',
      totalField: 'total',
    },
  });

  // 設定列表欄位
  function getBasicColumns(): BasicColumn[] {
    return [
      PlaceHoderNamegetBasicColumns
      {
        title: '操作',
        dataIndex: 'action',
      },
    ];
  }

  // 設定搜尋表單
  function getFormConfig(): Partial {
    return {
      
      schemas: [PlaceHoderNamegetFormConfigSchemas
      ],
    };
  }

  function openModalLoading(row = {}) {
    // 捕獲事件對象

    console.log('openModalLoading', row);

    openModal(true, row);
  }

  async function handleActionChick(event, row, action) {
    console.log('handleActionChick', row);
    // 執行刪除API
    const response = await PlaceHoderNameDelete({ placehoder_columns_id: row.placehoder_columns_id });
    console.log('response', response);
    reload();
  }

  function refreshTable() {
    // 調用刷新表格的方法
    reload();
  }
</script>
