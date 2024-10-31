<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Widget title'); ?></p>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="form-control widefat" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Show Brands\'s title'); ?></p>
        <select class="form-control widefat" name="<?php echo $this->get_field_name('show_title') ?>">
            <option value="0"<?php selected('0', $show_title); ?>><?php echo otwcbr_e('No'); ?></option>
            <option value="1"<?php selected('1', $show_title); ?>><?php echo otwcbr_e('Yes'); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Show product count (for Brands\'s title display)'); ?></p>
        <select class="form-control widefat" name="<?php echo $this->get_field_name('show_count') ?>">
            <option value="0"<?php selected('0', $show_count); ?>><?php echo otwcbr_e('No'); ?></option>
            <option value="1"<?php selected('1', $show_count); ?>><?php echo otwcbr_e('Yes'); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Show Brands\'s image)'); ?></p>
        <select class="form-control widefat" name="<?php echo $this->get_field_name('show_image') ?>">
            <option value="1"<?php selected('1', $show_image); ?>><?php echo otwcbr_e('Yes'); ?></option>
            <option value="0"<?php selected('0', $show_image); ?>><?php echo otwcbr_e('No'); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Show Brands\'s description)'); ?></p>
        <select class="form-control widefat" name="<?php echo $this->get_field_name('show_description') ?>">
            <option value="1"<?php selected('1', $show_description); ?>><?php echo otwcbr_e('Yes'); ?></option>
            <option value="0"<?php selected('0', $show_description); ?>><?php echo otwcbr_e('No'); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Hide empty brands'); ?></p>
        <select class="form-control widefat" name="<?php echo $this->get_field_name('hide_empty') ?>">
            <option value="0"<?php selected('0', $hide_empty); ?>><?php echo otwcbr_e('No'); ?></option>
            <option value="1"<?php selected('1', $hide_empty); ?>><?php echo otwcbr_e('Yes'); ?></option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <p><?php echo otwcbr_e('Widget class'); ?></p>
        <input type="text" id="<?php echo $this->get_field_id('widgetclass'); ?>" name="<?php echo $this->get_field_name('widgetclass'); ?>" value="<?php echo $widgetclass; ?>" class="form-control widefat" />
    </div>
</div>