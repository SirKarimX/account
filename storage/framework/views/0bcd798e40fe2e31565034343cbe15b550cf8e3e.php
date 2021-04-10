
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Employee-Detail')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <div class="row d-flex justify-content-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create payment')): ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                 <a href="#" data-size="2xl" data-url="<?php echo e(route('employee.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Payment')); ?>" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
                    <?php echo e(__('Create Payment')); ?>

                </a>
                </div>
            </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                 <a href="#" data-size="2xl" data-url="<?php echo e(route( 'employee.create-attendance')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Attendance')); ?>" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
                    <?php echo e(__('Create Attendance')); ?>

                </a>
                </div>
            </div>
        <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit user')): ?>
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-size="2xl" data-url="<?php echo e(route('employee.edit',$user['id'])); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit User')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete user')): ?>
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($user['id']); ?>').submit();" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]); ?>

                    <?php echo Form::close(); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection('content'); ?>
    
 
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <h3 class="small-title"><?php echo e(__('Employee Info')); ?></h3>
                <div class="row">
                 
                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('User Id')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['id']); ?></h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Email')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['email']); ?></h5>

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Name')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['name']); ?></h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Role')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['type']); ?></h5>
                             <h5 class="report-text gray-text mb-0"><?php echo e(__('Month')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e(now()->format('F Y')); ?></h5>
                        </div>
                    </div>
                     <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Salary')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['salary']); ?></h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Target')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e($user['target']); ?></h5>
                             <h5 class="report-text gray-text mb-0"><?php echo e(__('Commision')); ?></h5>
                            <h5 class="report-text mb-3"><?php echo e($user['commission']); ?>%</h5>
                                     <h5 class="report-text gray-text mb-0"><?php echo e(__('No not Attend')); ?></h5>
                            <h5 class="report-text mb-3"> 0 Day</h5>
                        </div>
                    </div>
<?php

 $totalInvoicePaymentSum=$user->userTotalPaymentInvoice($user['id']);
                      
                          $TotalPaymentSum=$user->userTotalPaymentSum($user['id']);
                           $balancePayment=$user['salary'] - $TotalPaymentSum;
                        $TotalCommission=$totalInvoicePaymentSum*$user['commission']/100;
                    ?>

                    <div class="col-md-6 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Received')); ?></h5>
                            <h5 class="report-text mb-3"><?php echo e(\Auth::user()->priceFormat($TotalPaymentSum)); ?></h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Achived')); ?></h5>
                            <h5 class="report-text mb-3"> <?php echo e(\Auth::user()->priceFormat($totalInvoicePaymentSum)); ?></h5></h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Commission Amount')); ?></h5>
                            <h5 class="report-text mb-3">  <?php if($Usercommission=$totalInvoicePaymentSum>=$user['target']): ?>
  
                            <?php echo e(\Auth::user()->priceFormat($TotalCommission)); ?>

    
                              <?php else: ?>

                                0

                            <?php endif; ?>
                        </h5>
                            <h5 class="report-text gray-text mb-0"><?php echo e(__('Balance')); ?></h5>
                            <h5 class="report-text mb-0">

<?php if($totalInvoicePaymentSum>=$user['target']): ?>
                                <?php echo e(\Auth::user()->priceFormat($balancePayment+$TotalCommission)); ?>

<?php else: ?>
 <?php echo e(\Auth::user()->priceFormat($balancePayment)); ?>

 <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4"><?php echo e(__('Payment')); ?></h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Date')); ?></th>
                                 <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                <th><?php echo e(__('Desc')); ?></th>
                               
                                
                               
                                    <th> <?php echo e(__('Action')); ?></th>
                               
                            </tr>
                            </thead>
                            <tbody>
                       <?php $__currentLoopData = $user->userPayment($user->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                <th> <?php echo e(\Auth::user()->dateFormat($payment->date)); ?></th>
                                <th><?php echo e($payment->amount); ?></th>
                               <th><?php echo e($payment->type); ?></th>
                                <th><?php echo e($payment->description); ?></th>
                             
                                
                               
                              <?php if(Gate::check('edit payment') || Gate::check('delete payment')): ?>
                                        <th class="action text-right">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit payment')): ?>
                                                <a href="#" class="edit-icon" data-url="<?php echo e(route('payment.edit',$payment->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Payment')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete payment')): ?>
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($payment->id); ?>').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['payment.destroy', $payment->id],'id'=>'delete-form-'.$payment->id]); ?>

                                                <?php echo Form::close(); ?>

                                            <?php endif; ?>
                                        </th>
                                    <?php endif; ?>


                                </tr>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
        
  
    <div class="row">
        <div class="col-12">

            <h5 class="h4 d-inline-block font-weight-400 mb-4"><?php echo e(__('Attendance')); ?></h5>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                
                               
                                    <th> <?php echo e(__('Action')); ?></th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            
                                <tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4"><?php echo e(__('Invoice')); ?></h5>

            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Invoice')); ?></th>
                                <th><?php echo e(__('Customer')); ?></th>
                                <th><?php echo e(__('Issue Date')); ?></th>
                                <th><?php echo e(__('Due Amount')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                    <th> <?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $user->userInvoice($user->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                          <td class="Id">
                                        <?php if(\Auth::guard('customer')->check()): ?>
                                            <a href="<?php echo e(route('customer.invoice.show',\Crypt::encrypt($invoice->id))); ?>"><?php echo e(AUth::user()->invoiceNumberFormat($invoice->invoice_id)); ?>

                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('invoice.show',\Crypt::encrypt($invoice->id))); ?>"><?php echo e(AUth::user()->invoiceNumberFormat($invoice->invoice_id)); ?>

                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e((!empty($invoice->customer)?$invoice->customer->name:'-')); ?></td>
                                    <td>
                                        <?php if(($invoice->issue_date < date('Y-m-d'))): ?>
                                            <p class="text-danger"> <?php echo e(\Auth::user()->dateFormat($invoice->issue_date)); ?></p>
                                        <?php else: ?>
                                            <?php echo e(\Auth::user()->dateFormat($invoice->issue_date)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(\Auth::user()->priceFormat($invoice->getDue())); ?></td>
                                    <td>
                                        <?php if($invoice->status == 0): ?>
                                            <span class="badge badge-pill badge-primary"><?php echo e(__(\App\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 1): ?>
                                            <span class="badge badge-pill badge-warning"><?php echo e(__(\App\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 2): ?>
                                            <span class="badge badge-pill badge-danger"><?php echo e(__(\App\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 3): ?>
                                            <span class="badge badge-pill badge-info"><?php echo e(__(\App\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 4): ?>
                                            <span class="badge badge-pill badge-success"><?php echo e(__(\App\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                        <td class="Action">
                                            <span>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('duplicate invoice')): ?>
                                                    <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="<?php echo e(__('Duplicate')); ?>" data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-<?php echo e($invoice->id); ?>').submit();">
                                                    <i class="fas fa-copy"></i>
                                                    <?php echo Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]); ?>

                                                        <?php echo Form::close(); ?>

                                                </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show invoice')): ?>
                                                    <?php if(\Auth::guard('customer')->check()): ?>
                                                        <a href="<?php echo e(route('customer.invoice.show',\Crypt::encrypt($invoice->id))); ?>" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="<?php echo e(__('Detail')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php else: ?>
                                                        <a href="<?php echo e(route('invoice.show',\Crypt::encrypt($invoice->id))); ?>" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="<?php echo e(__('Detail')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit invoice')): ?>
                                                    <a href="<?php echo e(route('invoice.edit',\Crypt::encrypt($invoice->id))); ?>" class="edit-icon" data-toggle="tooltip" data-original-title="<?php echo e(__('Edit')); ?>">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete invoice')): ?>
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($invoice->id); ?>').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]); ?>

                                                    <?php echo Form::close(); ?>

                                                                                              <?php endif; ?>
                                            </span>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\account\resources\views/employee/show.blade.php ENDPATH**/ ?>