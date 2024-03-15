import { usePage } from '@inertiajs/vue3';

const page = usePage();
const checkHasOneOfRoles = (roles) => {
    return page.props.roles.some(r => roles.includes(r))
}

export { checkHasOneOfRoles };
