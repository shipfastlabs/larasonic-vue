<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/shadcn/ui/card';
import { Badge } from '@/Components/shadcn/ui/badge';
import { Separator } from '@/Components/shadcn/ui/separator';
import { computed, ref } from 'vue';
import { Progress } from "@/Components/shadcn/ui/progress"
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from "@/Components/shadcn/ui/table"
import { format } from 'date-fns';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/shadcn/ui/accordion";
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/shadcn/ui/sheet";
import { Button } from "@/Components/shadcn/ui/button";

const props = defineProps({
    tuition: {
        type: Object,
        default: null,
    },
    transactions: {
        type: Array,
        required: true,
    },
    student: {
        type: Object,
        required: true
    }
});

// --- Computed Properties ---
const formatCurrency = (amount) => {
    if (amount === null || amount === undefined) { return '₱0'; }
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
};

const statusVariant = computed(() => {
    if (!props.tuition) return 'default';
    if (props.tuition.status === 'paid') return 'success';
    const today = new Date();
    const dueDate = props.tuition.due_date ? new Date(props.tuition.due_date) : null;
    if (dueDate && dueDate < today && props.tuition.total_balance > 0) { return 'destructive'; }
    return 'warning';
});

const paymentProgress = computed(() => {
    if (!props.tuition) return 0;
    const paidAmount = props.tuition.overall_tuition - props.tuition.total_balance;
    return Math.max(0, Math.min(100, (paidAmount / props.tuition.overall_tuition) * 100));
});

const totalPaid = computed(() => {
    if (!props.tuition) return 0;
    return props.tuition.overall_tuition - props.tuition.total_balance;
});

const sortedTransactions = computed(() => {
    return [...props.transactions].sort((a, b) => new Date(b.transaction_date).getTime() - new Date(a.transaction_date).getTime());
});

const formattedDueDate = computed(() => {
    return props.tuition?.due_date ? format(new Date(props.tuition.due_date), 'MMMM dd, yyyy') : 'N/A';
});

// Calculate total settlement amount for each transaction
const detailedTransactions = computed(() => {
    return sortedTransactions.value.map(transaction => {
        let totalSettlementAmount = 0;
        const details = [];

        // Helper function to add to details and total
        const addSettlement = (label, amount) => {
          if (amount) {
            const numericAmount = Number(amount); // Ensure it's a number
            details.push({ label, amount: numericAmount });
            totalSettlementAmount += numericAmount;
          }
        };

        addSettlement('Registration Fee', transaction.settlements?.registration_fee);
        addSettlement('Tuition Fee', transaction.settlements?.tuition_fee);
        addSettlement('Miscellaneous Fee', transaction.settlements?.miscelanous_fee);
        addSettlement('Diploma/Certificate', transaction.settlements?.diploma_or_certificate);
        addSettlement('Transcript of Records', transaction.settlements?.transcript_of_records);
        addSettlement('Certification', transaction.settlements?.certification);
        addSettlement('Special Exam', transaction.settlements?.special_exam);
        addSettlement('Others', transaction.settlements?.others);

        return {
            ...transaction,
            totalSettlementAmount, // Add the total
            details,
        };
    });
});

// --- Refs for Interactivity ---
const isSheetOpen = ref(false);
const selectedTransaction = ref(null);

// --- Methods ---
const openTransactionDetails = (transaction) => {
    selectedTransaction.value = transaction;
    isSheetOpen.value = true;
};

</script>

<template>
    <AppLayout title="Tuition Fees">
        <div class="md:container mx-auto px-4 py-6 space-y-6">
            <h1 class="text-2xl font-bold print:text-xl">Tuition Overview</h1>
            <p class="text-muted-foreground print:text-sm">
                {{ tuition ? `${tuition.school_year} - Semester ${tuition.semester}` : 'Loading...' }}
            </p>

            <div v-if="tuition" class="space-y-6">

                <!-- Summary Card -->
                <Card class="print:border-0">
                    <CardHeader class="print:hidden">
                        <CardTitle>Account Summary</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 print:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium">Student:</p>
                                <p class="text-lg">{{ student.first_name }} {{ student.middle_name }} {{ student.last_name }} ({{ student.id }})</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Due Date:</p>
                                <p class="text-lg">{{ formattedDueDate }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Status:</p>
                                <Badge :variant="statusVariant">{{ tuition.status || 'N/A' }}</Badge>
                            </div>
                        </div>
                        <Separator class="my-4 print:hidden" />
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-sm text-muted-foreground">Total Paid</p>
                                <p class="text-xl font-bold">{{ formatCurrency(totalPaid) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-muted-foreground">Remaining Balance</p>
                                <p class="text-xl font-bold text-red-500">{{ formatCurrency(tuition.total_balance) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-muted-foreground">Overall Tuition</p>
                                <p class="text-xl font-bold">{{ formatCurrency(tuition.overall_tuition) }}</p>
                            </div>
                        </div>
                        <Separator class="my-4 print:hidden" />
                        <div class="print:hidden">
                            <p class="text-sm text-muted-foreground mb-1">Payment Progress</p>
                            <Progress :value="paymentProgress" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Accordion for Details -->
                <Accordion type="single"  collapsible class="space-y-4 print:block">
                    <AccordionItem value="item-1">
                        <AccordionTrigger>Tuition Breakdown</AccordionTrigger>
                        <AccordionContent>
                            <Table class="print:border-0">
                                <TableHeader class="print:hidden">
                                    <TableRow>
                                        <TableHead>Description</TableHead>
                                        <TableHead class="text-right">Amount</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow>
                                        <TableCell>Total Tuition</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.total_tuition) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Overall Tuition</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.overall_tuition) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Discount</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.discount) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Downpayment</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.downpayment) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Lectures</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.total_lectures) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Laboratory</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.total_laboratory) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Miscellaneous</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(tuition.total_miscelaneous_fees) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Balance</TableCell>
                                        <TableCell class="text-right text-red-500">{{ formatCurrency(tuition.total_balance) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell class="font-bold">Total Paid</TableCell>
                                        <TableCell class="text-right font-bold">{{ formatCurrency(totalPaid) }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </AccordionContent>
                    </AccordionItem>

                    <AccordionItem value="item-2">
                        <AccordionTrigger>Transaction History</AccordionTrigger>
                        <AccordionContent>
                            <Table class="print:border-0">
                                <TableHeader class="print:hidden">
                                    <TableRow>
                                        <TableHead>Date</TableHead>
                                        <TableHead>Transaction #</TableHead>
                                        <TableHead class="text-right">Amount</TableHead>
                                        <TableHead>Status</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="transaction in detailedTransactions" :key="transaction.id"
                                              @click="openTransactionDetails(transaction)"
                                              class="cursor-pointer hover:bg-muted print:bg-transparent">
                                        <TableCell>{{ transaction.transaction_date }}</TableCell>
                                        <TableCell>{{ transaction.transaction_number }}</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(transaction.totalSettlementAmount) }}</TableCell>
                                        <TableCell>
                                            <Badge :variant="transaction.status === 'completed' ? 'success' : 'default'">
                                                {{ transaction.status }}
                                            </Badge>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="sortedTransactions.length === 0">
                                        <TableCell colspan="4" class="text-center">No transactions found.</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>

                <!-- Transaction Details Sheet -->
                <Sheet v-model:open="isSheetOpen">
                    <SheetContent side="right" class="overflow-y-auto print:hidden">
                        <SheetHeader>
                            <SheetTitle>Transaction Details</SheetTitle>
                            <SheetDescription v-if="selectedTransaction">
                                Transaction #{{ selectedTransaction.transaction_number }} on {{ selectedTransaction.transaction_date }}
                            </SheetDescription>
                        </SheetHeader>
                        <div class="py-4 space-y-4" v-if="selectedTransaction">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Description</TableHead>
                                        <TableHead class="text-right">Amount</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="detail in selectedTransaction.details" :key="detail.label">
                                        <TableCell>{{ detail.label }}</TableCell>
                                        <TableCell class="text-right">{{ formatCurrency(detail.amount) }}</TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell class="font-bold">Total</TableCell>
                                        <TableCell class="font-bold text-right">{{ formatCurrency(selectedTransaction.totalSettlementAmount) }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                            <p>Status:
                                <Badge :variant="selectedTransaction.status === 'completed' ? 'success' : 'default'">
                                    {{ selectedTransaction.status }}
                                </Badge>
                            </p>
                            <p v-if="selectedTransaction.description">
                                Description: {{selectedTransaction.description}}
                            </p>
                        </div>
                        <SheetFooter>
                            <SheetClose as-child>
                                <Button type="button" variant="secondary">Close</Button>
                            </SheetClose>
                        </SheetFooter>
                    </SheetContent>
                </Sheet>

            </div>

            <div v-else class="text-center py-8 text-muted-foreground">
                No tuition information available for the current semester.
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    .print\\:hidden { display: none; }
    .print\\:text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .print\\:text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .print\\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .print\\:border-0 { border-width: 0px; }
    .print\\:block { display: block; }
    /* Force accordion to be open when printing */
    .print\\:block .accordion-content {
        display: block !important;
        height: auto !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
}
</style> 