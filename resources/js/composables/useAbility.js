import { usePage } from '@inertiajs/vue3'

export function useAbility() {
    const page = usePage()
    const perms = (page.props.auth?.user?.permissions) || []
    const roles = (page.props.auth?.user?.roles) || []

    function can(permission) {
        return perms.includes(permission)
    }

    function hasRole(role) {
        return roles.includes(role)
    }

    return { can, hasRole }
}
