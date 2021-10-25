<?php


namespace ColibriWP\Theme\Customizer\Controls;


use ColibriWP\Theme\Translations;

class SelectControl extends VueControl {
    public $type = 'colibri-select';

    protected function printVueContent() {
        ?>
        <el-select v-model="value" :size="size" @change="setValue"
                   placeholder="<?php Translations::escAttrE( 'select' ); ?>">
            <el-option
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
            </el-option>
        </el-select>
        <?php
    }
}
