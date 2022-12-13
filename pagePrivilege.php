<?php

$config = parse_ini_file(__DIR__ . "/jsheetconfig.ini");
require_once($_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/role.php");

$currentUrl = $_SERVER['REQUEST_URI'];


$orgAdminPages = array(
  "/organization/attendance/attendance.php",
  "/organization/attendance/viewAttendance.php",
  "/organization/attendance/attendancereport.php",
  "/organization/attendance/setting.php",

  "/organization/vendor/viewVendor.php",
  "/organization/vendor/editVendor.php",
  "/organization/client/viewClient.php",
  "/organization/report/report.php",
  "/organization/vendor/addVendorClient.php",
  "/organization/vendor/addVendor.php",
  "/organization/vendor/addJobList.php",
  "/organization/client/addClient.php",
  "/organization/client/editClient.php",
  "/organization/client/fileManager.php",
  "/organization/client/addSignatory.php",
  "/organization/client/editSignatory.php",
  "/organization/client/viewSignatory.php",
  "/organization/client/addIndividualMember.php",
  "/organization/client/addCorporate Member.php",

  "/organization/receipt/receipt.php",
  "/organization/receipt/mailReceipt.php",

  "/organization/role/role.php",

  "/organization/staff/editStaff.php",
  "/organization/staff/viewStaff.php",
  "/organization/staff/addStaff.php",
  "/organization/staff/myTask/viewTask.php",
  "/organization/staff/myTask/updateJob.php",

  "/organization/auditors/ccmsEligiblity.php",
  "/organization/auditors/epfLetterApproval.php",
  "/organization/auditors/examinationList.php",
  "/organization/auditors/articles.php",
  "/organization/auditors/researchGrant.php",
  "/organization/auditors/technicalInquiry.php",
  "/organization/auditors/professionalDevelopment.php",
  "/organization/auditors/candidatesReporting.php",
  "/organization/auditors/GenerateECertificate.php",
  "/organization/auditors/eCertificateHistory.php",
  "/organization/auditors/eCertificateStatus.php",
  "/organization/auditors/certifiedMembers.php",
  "/organization/auditors/e-sign.php",
  "/organization/auditors/memershipList.php",
  "/organization/auditors/memershipOverList.php",
  "/organization/auditors/postMember.php",
  "/organization/auditors/terminationProcess.php",
  "/organization/auditors/pdRegisterProgram.php",
  "/organization/auditors/pdRegisterProgram.php",
  "/organization/auditors/examinationProgress.php",
  "/organization/auditors/qar.php",
  "/organization/auditors/eventGenerator.php",
  "/organization/auditors/eventGeneratedView.php",
  "/organization/auditors/eventMarketing.php",
  "/organization/auditors/eventRegister.php",
  "/organization/auditors/applicantsReport.php",
  "/organization/auditors/memberDemographicReport.php",
  "/organization/auditors/memberEligibleReport.php",
  "/organization/auditors/memberHistoryReport.php",
  "/organization/auditors/memberListReport.php",
  "/organization/auditors/paymentHistoryReport.php",
  "/organization/auditors/paymentOutstandingReport.php",
  "/organization/auditors/rejoinOrUpgradingReport.php",
  "/organization/auditors/submissionReport.php",
  "/organization/auditors/membershipSummaryReport.php",
  "/organization/auditors/requisition.php",
  "/organization/auditors/recruitment.php",
  "/organization/auditors/resignation.php",
  "/organization/auditors/employeeDrive.php",
  "/organization/auditors/trainingDevelopment.php",
  "/organization/auditors/performanceManagement.php",
  "/organization/auditors/api.php",

  "/organization/department/addDepartment.php",
  "/organization/department/editDepartment.php",
  "/organization/department/viewDepartment.php",
  "/organization/department/addDesignation.php",
  "/organization/department/editDesignation.php",
  "/organization/department/viewDesignation.php",

  "/organization/product/viewProduct.php",
  "/organization/product/product.php",
  "/organization/product/option/addBrand.php",
  "/organization/product/option/editBrand.php",
  "/organization/product/option/viewBrand.php",
  "/organization/product/option/addType.php",
  "/organization/product/option/editType.php",
  "/organization/product/option/viewType.php",
  "/organization/product/report/report.php",
  "/organization/product/sentoutlist.php",
  "/organization/product/editProduct.php",
  "/organization/product/sentProduct.php",
  "/organization/product/genbarcode.php",
  "/organization/product/barcode.php",
  "/organization/product/generate_barcode.php",
  "/organization/product/store.php",
  "/organization/client/newClientRequest.php",
  "/organization/client/potentialClients.php",
  "/organization/product/store-report.php",
  "/organization/product/report.php",

    "/document_sign_sow/views/member_view_pdf.php",
    "/document_sign_sow/views/view_pdf.php",
    "/document_sign_sow/views/index.php",
    "/document_sign_sow/views/member_view_apdf.php",



  "/organization/calendar/calendar.php",

  "/organization/myOrganization/updateMyOrg.php",

  "/organization/complaint/makeComplaint.php",
  "/organization/complaint/completed.php",
  "/organization/complaint/uncompleted.php",
  "/organization/complaint/viewSLA.php",

  "/organization/project/createProject.php",
  "/organization/project/viewProject.php",
  "/organization/project/groupProject.php",
  "/organization/project/reportProject.php",

  "/organization/quotation/createQuotation/index2.php",
  "/organization/quotation/createQuotation.php",
  "/organization/quotation/mailQuotation.php",
  "/organization/quotation/viewQuotation.php",
  "/organization/invoice/createInvoice.php",
  "/organization/invoice/mailInvoice.php",
  "/organization/invoice/report/report.php",
  "/organization/InvoiceC1/createInvoiceC1.php",
  "/organization/InvoiceC1/mailInvoiceC1.php",
  "/organization/InvoiceC1/viewInvoiceC1.php",
  "/organization/InvoiceC1/report/reportC1.php",
  "/organization/invoiceST/createInvoiceSt.php",
  "/organization/invoiceST/mailInvoiceSt.php",
  "/organization/invoiceST/viewInvoiceSt.php",

  "/organization/services/addServices.php",
  "/organization/services/servicesDescription.php",

  "/organization/recurringInvoice/index.php",
  "/organization/recurringInvoice/scheduler-cron.php",
  "/organization/recurringInvoice/scheduler-edit.php",
  "/organization/recurringInvoice/scheduler-insert.php",
  "/organization/recurringInvoice/scheduler-list.php",
  "/organization/recurringInvoice/scheduler.php",
  "/organization/recurringInvoice/get-invoice.php",
  "/organization/recurringInvoice/db.php",
  "/organization/recurringInvoice/createRecurringInvoice.php",

  "/organization/invoice/viewInvoice.php",
  "/organization/setting/qiSetting.php",

  "/organization/recurring/createRecurring.php",
  "/organization/recurring/recurringForm.php",
  "/organization/recurring/mailDetail.php",
  "/organization/recurring/recurringReport.php",

  "/organization/report/quotation_invoice.php",

  "/organization/payroll/payrollSetting.php",
  "/organization/payroll/payroll.php",
  "/organization/payroll/payrollreport.php",
  "/organization/payroll/print.php",
  "/organization/payroll/mailPayslip.php",
  "/organization/payroll/viewPayslip.php",
  "/organization/payroll/eaform.php",
  "/organization/payroll/vieweaform.php",
  "/organization/payroll/eareport.php",
  "/organization/payroll/eaeditform.php",
  "/organization/payroll/addOfficerDetails.php",
  "/organization/payroll/editOfficerDetails.php",
  "/organization/payroll/viewOfficerDetails.php",


  "/organization/purchaseOrder/manageSuppliers.php",
  "/organization/purchaseOrder/createPO.php",
  "/organization/purchaseOrder/viewPO.php",

  "/organization/claim/claimForm.php",
  "/organization/claim/viewClaim.php",
  "/organization/claim/claimReport.php",

  "/organization/leave/applyLeave.php",
  "/organization/leave/allLeaves.php",
  "/organization/leave/leaveDetails.php",
  "/organization/leave/pendingLeaves.php",
  "/organization/leave/approvedLeaves.php",
  "/organization/leave/RejectedLeaves.php",
  "/organization/leave/pendingCountLeaves.php",
  "/organization/leave/staffLeaveUpdate.php",
  "/organization/leave/leaveUpdateAll.php",

  "/organization/vehicle/viewVehicle.php",
  "/organization/vehicle/vehicle.php",
  "/organization/vehicle/editVehicle.php",
  "/organization/vehicle/option/addBrand.php",
  "/organization/vehicle/option/addType.php",
  "/organization/vehicle/option/editBrand.php",
  "/organization/vehicle/option/editType.php",
  "/organization/vehicle/option/viewBrand.php",
  "/organization/vehicle/option/viewType.php",

  "/organization/claim/addType.php",
  "/organization/claim/editType.php",
  "/organization/claim/viewType.php",


  "/organization/vehicle/option/addBrand.php",
  "/organization/vehicle/option/editBrand.php",
  "/organization/vehicle/option/viewBrand.php",

  "/organization/vehicle/option/addCategory.php",
  "/organization/vehicle/option/editCategory.php",
  "/organization/vehicle/option/viewCategory.php",

  "/organization/vehicle/option/addType.php",
  "/organization/vehicle/option/editType.php",
  "/organization/vehicle/option/viewType.php",

  "/organization/vehicle/editVehicle.php",
  "/organization/vehicle/vehicle.php",
  "/organization/vehicle/viewVehicle.php",

  "/organization/vehicle/option/addMaintenanceFor.php",
  "/organization/vehicle/option/editMaintenanceFor.php",
  "/organization/vehicle/option/viewMaintenanceFor.php",

  "/organization/vehicle/editMaintenance.php",
  "/organization/vehicle/maintenance.php",
  "/organization/vehicle/viewMaintenance.php",
  "/organization/reports/maintenanceReport.php",
  "/organization/reports/tripReport.php",
  "/organization/reports/vehicleReport.php",

  "/organization/trip/trip.php",
  "/organization/trip/editTrip.php",
  "/organization/trip/viewTrip.php",
  "/organization/invoiceTrip/createTripInvoice.php",
  "/query/tripInvoice.php",
  "/phpfunctions/tripInvoice.php",
  "/organization/invoiceTrip/mailTripInvoice.php",
  "/query/tripInvoiceItem.php",
  "/organization/invoiceTrip/viewTripInvoice.php",
  "/query/tripInvoiceMailList.php",

  "/organization/share/share.php",

  "/organization/bill/bill.php",
  "/organization/bill/viewBill.php",
  "/organization/bill/option/addCategory.php",
  "/organization/bill/option/addExpensFor.php",
  "/organization/bill/option/editCategory.php",
  "/organization/bill/option/editExpensFor.php",
  "/organization/bill/option/viewCategory.php",
  "/organization/bill/option/viewExpensFor.php",
  "/organization/bill/purchase.php",

  "/organization/help.php",
  "/organization/mailConfiguration.php",

  "/organization/terms.php",

  "/organization/profitLoss/profitLoss.php",

  "/schedule/schedule.php",

  "/organization/configuration/appConfiguration.php",

  "/organization/pdfSign/uploadPdf.php",
  "/organization/pdfSign/pdfList.php",
  "/organization/pdfSign/pdf.php",
  "/organization/pdfSign/signPdf.php",
  "/organization/pdfSign/viewPdf.php",
  "/organization/membership-subscription/subscriber.php",
  "/organization/membership-subscription/activatedSubscriber.php",
  "/organization/membership-subscription/addMembership.php",
  "/organization/membership-subscription/membershipPlan.php",
  "/organization/membership-subscription/terminateRequest.php",
  "/organization/membership-subscription/terminateAccount.php",
  "/organization/store/addBanner.php",
  "/organization/store/editBanner.php",
  "/organization/store/viewBanner.php",
  "/document_sign_sow/views/index.php",
  "/document_sign_sow/views/member_view_pdf.php",
  "/document_sign_sow/views/view_pdf.php",
  "/document_sign_sow/views/member_view_apdf.php",



);

$orgStaffPages = array(
  "/organization/staff/updateJob.php",
  "/organization/staff/assignedTask/viewTask.php"
);

$calendar = array(
  "/organization/calendar/calendar.php",
);

$clientPages = array(
  "/client/complaint/makeComplaint.php",
  "/client/complaint/viewComplaint.php",
  "/client/request/makeRequest.php",
  "/client/request/viewRequest.php",
  "/schedule/clientview/booking/createbooking.php",
  "/schedule/clientview/booking/viewbooking.php",
  "/cleanto/front/thankyou.php"
);


$vendorPages = array(
  "/vendor/updateJob.php",
  "/vendor/assignedTask/viewTask.php"

);

$clientStore = array(
  "/client/complaint/makeComplaint.php",
  "/client/complaint/viewComplaint.php",
  "/client/store/store.php",
  "/client/store/profile.php",
  "/client/store/product.php",
  "/client/store/orderlist.php",
  "/client/store/invoice.php",
  "/client/store/checkout.php",
  "/client/files/sharedMe.php",
  "/client/files/myFiles.php",
  "/client/store/epf-withdrawal.php",
  "/client/store/research-grant.php",
  "/client/store/technical-inquiry.php",
  "/client/store/technical-status.php",
  "/client/store/pd-enquiry.php",
  "/client/store/pd-registration.php",
  "/client/store/certification.php",
  "/client/store/examination.php",
  "/client/store/events.php",
  "/client/store/renewal.php",
  "/client/store/upgrade.php",
  "/client/store/account-termination.php",
  "/client/store/print.php",
  "/client/store/contact-us.php",

  "/client/pdfSign/pdfList.php",
  "/client/pdfSign/pdf.php",
  "/client/pdfSign/signPDF.php",
  "/client/pdfSign/viewPDF.php",

);



if (isOrganization() == true) {

  if (isNormalUser()) {
    checkAccess($currentUrl, $orgStaffPages);
  } else {
    checkAccess($currentUrl, $orgAdminPages);
  }
} else if (isVendor() == true) {

  checkAccess($currentUrl, $vendorPages);
} else if (isClient() == true) {
  checkAccess($currentUrl, $clientPages);
} else if (isClientStore() == true) {
  checkAccess($currentUrl, $clientStore);
}



function checkAccess($url, $pages)
{
  $config = parse_ini_file(__DIR__ . "/jsheetconfig.ini");
  $granted = true;
  if (strpos($url, '/home.php') !== false) {
    // do nothing
  } else {
    foreach ($pages as $page) {
      $page = $config['appRoot'] . $page;
      //echo $url."<br/>".$pages[0]."<br/>".$page;
      if (($url === $page)) {
        //echo "granted<br/>";
        $granted = true;
        break;
      } else {
        //echo "not-granted<br/>";
        $granted = false;
      }
    }
  }
  if ($granted == false) {
    // echo "redirected<br/>";

//    header('Location:https://' . $_SERVER['HTTP_HOST'] . $config['appRoot'] . '/home.php');
  }
}
