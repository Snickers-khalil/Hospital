<div class="content-wrapper bg-light">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        <?php echo lang('request_a_appointment'); ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home'); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('request_a_appointment'); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <form role="form" action="appointment/addRequest" method="post">
                                <div class="form-group">
                                    <label for="requestDoctor"><?php echo lang('doctor'); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control" id="requestDoctor" name="doctor" style="height: auto;" required>
                                        <option value=""><?php echo lang('select_doctor'); ?></option>
                                        <?php foreach ($doctors as $doctor) { ?>
                                            <option value="<?php echo $doctor->id; ?>"><?php echo htmlspecialchars($doctor->name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="requestDate"><?php echo lang('date'); ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="requestDate" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="requestRemarks"><?php echo lang('remarks'); ?></label>
                                    <textarea class="form-control" id="requestRemarks" name="remarks" rows="3" maxlength="500"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary float-right"><?php echo lang('submit'); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
