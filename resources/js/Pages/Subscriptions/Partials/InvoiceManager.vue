<script setup>
import { Icon } from '@iconify/vue'
import ActionSection from '@/components/ActionSection.vue'
import Button from '@/components/ui/button/Button.vue'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

defineProps({
  invoices: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <ActionSection>
    <template #title>
      Manage Invoices
    </template>

    <template #description>
      <div class="flex items-center space-x-2">
        <p>View and download your past invoices.</p>
      </div>
    </template>

    <template #content>
      <div class="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Invoice #</TableHead>
              <TableHead>Date</TableHead>
              <TableHead>Customer</TableHead>
              <TableHead>Status</TableHead>
              <TableHead class="text-right">
                Amount
              </TableHead>
              <TableHead class="text-right">
                Download
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="invoice in invoices" :key="invoice.id">
              <TableCell class="font-medium">
                {{ invoice.id }}
              </TableCell>
              <TableCell>{{ new Date(invoice.created * 1000).toLocaleDateString() }}</TableCell>
              <TableCell>{{ invoice.customer_name }}</TableCell>
              <TableCell>{{ invoice.status }}</TableCell>
              <TableCell class="text-right">
                ${{ (invoice.amount_paid / 100).toFixed(2) }}
              </TableCell>
              <TableCell class="text-center">
                <Button variant="ghost" as="a" :href="invoice.hosted_invoice_url" target="_blank">
                  <Icon icon="lucide:download" />
                </Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </template>
  </ActionSection>
</template>
