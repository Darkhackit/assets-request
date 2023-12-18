import type { VerticalNavItems } from '@/@layouts/types'

export default [
  {
    title: 'Home',
    to: { name: 'index' },
    icon: { icon: 'mdi-home-outline' },
    action: 'read',
    subject: 'dashboard',
  },
  {
    title: 'Employees',
    to: { name: 'employee' },
    icon: { icon: 'mdi-file-document-outline' },
    action: 'read',
    subject: 'second',
  },
  {
    title: 'Access Control',
    action: 'read',
    subject: 'dashboard',
    children: [
      {
        title:"Permissions",
        to:'access-permissions',
        action: 'read',
        subject: 'dashboard',
      },
      {
        title:"Roles",
        to:'access-roles',
        action: 'read',
        subject: 'dashboard',
      },
    ]
  },
] as VerticalNavItems
