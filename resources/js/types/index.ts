export * from './auth';
export * from './cms';

import type { User } from './auth';
import type {
    Admissions,
    AnnouncementBar,
    NavPayload,
    PageCollections,
    SchoolSettings,
    SiteTheme,
} from './cms';

/**
 * Props Inertia yang dibagikan untuk seluruh halaman web publik.
 * @see App\Http\Middleware\HandleInertiaRequests
 */
export interface SharedPageProps {
    name: string;
    auth: { user: User | null };
    navigation: NavPayload;
    siteSettings: SchoolSettings;
    siteTheme: SiteTheme | null;
    announcementBar: AnnouncementBar | null;
    admissions: Admissions;
    pageCollections: PageCollections;
    [key: string]: unknown;
}
