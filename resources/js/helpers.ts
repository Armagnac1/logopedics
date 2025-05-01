import { usePage } from '@inertiajs/vue3';

type Role = string;

const page = usePage<{ props: { roles: Role[] } }>();

const checkHasOneOfRoles = (roles: Role[]): boolean => {
    return page.props.roles.some((r: Role) => roles.includes(r));
}

export { checkHasOneOfRoles };
