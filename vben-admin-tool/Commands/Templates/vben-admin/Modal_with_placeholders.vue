<template>
  <BasicModal v-bind="$attrs" @register="register" @ok="handleSubmit" @cancel="resetFields">
    <BasicForm
      @register="registerForm"
      :schemas="schemas"
      :showResetButton="false"
      :showSubmitButton="false"
    />
  </BasicModal>
</template>

<script lang="ts" setup>
  import { ref, defineEmits } from 'vue';

  import { BasicModal, useModalInner } from '@/components/Modal';

  import { PlaceHoderNameUpdate, PlaceHoderNameCreate } from '@/api/PlaceHoderName';
  import { BasicForm, FormSchema, useForm } from '@/components/Form';

  const emit = defineEmits(['refresh-list']);
  const modalData = ref({}); // 用來保存從 index.vue 傳過來的數據
  const isUpdate = ref(false); // 用來判斷是否為編輯狀態
  const schemas: FormSchema[] = [
  PlaceHoderNameFormSchema
    
  ];
  const [registerForm, { setFieldsValue, resetFields, getFieldsValue, updateSchema }] = useForm({
    labelWidth: 100,
    baseColProps: { span: 24 },
    actionColOptions: {
      span: 23,
    },
  });
  const [register, { setModalProps, closeModal }] = useModalInner(async (data) => {
    resetFields(); // 重置表單
    setModalProps({ title: data.placehoder_columns_id ? '編輯' : '新增' }); // 設定 modal 的 title
    if (data.placehoder_columns_id) {
      isUpdate.value = true; // 編輯狀態
      // updateSchema([
      //   { field: 'placehoder_columns_password', componentProps: { placeholder: '如果不要修改密碼請留空' } },
      // ]);
    } else {
      isUpdate.value = false; // 新增狀態
      // updateSchema([{ field: 'placehoder_columns_password', componentProps: { placeholder: '輸入密碼' } }]);
    }
    updateSchema([{ field: 'placehoder_columns_id', componentProps: { disabled: isUpdate.value } }]);
    modalData.value = data; // 將傳入的數據保存到 modalData
    setFieldsValue(modalData.value);
  });

  const handleSubmit = async () => {
    // 抓取表單的值
    const values = getFieldsValue();
    console.log('values', values);
    console.log('isUpdate', isUpdate.value);
    if (isUpdate.value) {
      try {
        // 編輯
        await PlaceHoderNameUpdate(values);
        // 如果編輯成功則關閉 Modal 並重新整理列表
        closeModal();
        emit('refresh-list');
      } catch (error) {
        console.log('error', error);
      }
    } else {
      // 新增
      try {
        await PlaceHoderNameCreate(values);
        closeModal();
        emit('refresh-list');
      } catch (error) {
        console.log('error', error);
      }
    }
  };
</script>
