import type { AppRouteModule } from '@/router/types';

import { LAYOUT } from '@/router/constant';

const placehoder_name: AppRouteModule = {
  path: '/placehoder_name',
  name: 'placehoder_name',
  component: LAYOUT,
  redirect: '/placehoder_name/list',
  meta: {
    orderNo: 500,
    icon: 'ion:skull',
    title: 'placehoder_name',
  },
  children: [
    {
      path: 'list',
      name: 'placehoder_nameList',
      meta: {
        title: 'placehoder_nameList',
        hideMenu: false, // 隐藏菜单
      },
      component: () => import('@/views/placehoder_name/list/index.vue'),
    },
  ],
};

export default placehoder_name;
