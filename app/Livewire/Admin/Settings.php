<?php

namespace App\Livewire\Admin;

use App\Models\AttendanceSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Settings extends Component
{
    public string $check_in_start = '06:00';
    public string $on_time_until = '07:00';
    public string $late_until = '10:00';
    public string $auto_absent_at = '10:00';
    public bool $allow_checkout = true;
    public string $check_out_start = '15:00';
    public string $check_out_end = '17:00';

    public string $successMessage = '';

    public function mount()
    {
        $setting = AttendanceSetting::current();
        $this->check_in_start = substr($setting->check_in_start, 0, 5);
        $this->on_time_until = substr($setting->on_time_until, 0, 5);
        $this->late_until = substr($setting->late_until, 0, 5);
        $this->auto_absent_at = substr($setting->auto_absent_at, 0, 5);
        $this->allow_checkout = (bool) $setting->allow_checkout;
        $this->check_out_start = $setting->check_out_start ? substr($setting->check_out_start, 0, 5) : '15:00';
        $this->check_out_end = $setting->check_out_end ? substr($setting->check_out_end, 0, 5) : '17:00';
    }

    public function save()
    {
        $this->validate([
            'check_in_start' => 'required',
            'on_time_until' => 'required',
            'late_until' => 'required',
            'auto_absent_at' => 'required',
            'check_out_start' => 'required_if:allow_checkout,true',
            'check_out_end' => 'required_if:allow_checkout,true',
        ]);

        $setting = AttendanceSetting::current();
        $setting->update([
            'check_in_start' => $this->check_in_start . ':00',
            'on_time_until' => $this->on_time_until . ':00',
            'late_until' => $this->late_until . ':00',
            'auto_absent_at' => $this->auto_absent_at . ':00',
            'allow_checkout' => $this->allow_checkout,
            'check_out_start' => $this->check_out_start ? ($this->check_out_start . ':00') : null,
            'check_out_end' => $this->check_out_end ? ($this->check_out_end . ':00') : null,
        ]);

        $this->successMessage = 'Pengaturan jam absensi masuk & pulang berhasil diperbarui.';
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
