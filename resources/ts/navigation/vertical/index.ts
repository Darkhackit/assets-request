import type { VerticalNavItems } from '@/@layouts/types'

export default [
  {
    title: 'Home',
    to: { name: 'index' },
    icon: { icon: 'mdi-home-outline' },
    action: 'list',
    subject: 'permission',
  },
  {
    title: 'Employees',
    to: { name: 'employee' },
    icon: { icon: 'mdi-users-outline' },
    action: 'list',
    subject: 'employee',
  },
  {
    title: 'Request Assets',
    to: { name: 'send-invoice' },
    icon: { icon: 'mdi-file-document-outline' },
    action: 'list',
    subject: 'invoice',
  },
  {
    title: 'Vendors',
    to: { name: 'vendors' },
    icon: { icon: 'mdi-user-outline' },
    action: 'list',
    subject: 'vendor',
  },
  {
    title: 'Vendor Branches',
    to: { name: 'vendor-branches' },
    icon: { icon: 'mdi-house-outline' },
    action: 'list',
    subject: 'vendor',
  },
  {
    title: 'Pending Request',
    to: { name: 'pending-invoice' },
    icon: { icon: 'mdi-file-document-outline' },
    action: 'update',
    subject: 'pending',
  },
  {
    title: 'Access Control',
    action: 'list',
    subject: 'permission',
    children: [
      {
        title:"Permissions",
        to:'access-permissions',
        action: 'list',
        subject: 'permission',
      },
      {
        title:"Roles",
        to:'access-roles',
        action: 'list',
        subject: 'role',
      },
    ]
  },
] as VerticalNavItems
