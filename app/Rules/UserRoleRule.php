<?php

namespace App\Rules;

use App\Models\Role;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserRoleRule implements ValidationRule
{
    protected Role $userRole;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Role $userRole)
    {
        $this->userRole = $userRole;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // I'm not sure to use the guard pattern here because Laravel docs dont's specify
        // if only the fail closure should be called or if $fail(...) and then a return works.
        $role = Role::findOrFail((int) $value);
        if ($this->userRole->id === $role->id)
            return;
        if (!(($role->hierarchy > $this->userRole->hierarchy) || $this->userRole->hierarchy === 0)) {
            $fail('The :attribute have a higher hierarchy than the allowed.');
        }
    }
}
