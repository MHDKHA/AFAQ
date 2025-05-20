// resources/js/Pages/Tools/Show.jsx
import React from 'react';
import { usePage, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function ToolShow({ tool, domains }) {
    const { auth, locale } = usePage().props;

    return (
        <MainLayout title={locale === 'ar' ? tool.name_ar : tool.name}>
            <div className="bg-white shadow overflow-hidden sm:rounded-lg">
                <div className="px-4 py-5 sm:px-6">
                    <h2 className="text-2xl font-bold text-gray-900">
                        {locale === 'ar' ? tool.name_ar : tool.name}
                    </h2>
                    <p className="mt-1 max-w-2xl text-sm text-gray-500">
                        {locale === 'ar' ? tool.description_ar : tool.description}
                    </p>
                </div>

                {!auth.user ? (
                    <div className="px-4 py-5 sm:p-6 bg-gray-50">
                        <div className="text-center">
                            <h3 className="text-lg leading-6 font-medium text-gray-900">
                                Login or Register to Start Assessment
                            </h3>
                            <div className="mt-5 flex justify-center">
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
                                >
                                    Login
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Register
                                </Link>
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="px-4 py-5 sm:p-6">
                        <div className="text-center">
                            <Link
                                href={route('assessment.start', tool.slug)}
                                method="post"
                                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Start Assessment
                            </Link>
                        </div>
                    </div>
                )}

                <div className="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl className="sm:divide-y sm:divide-gray-200">
                        <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt className="text-sm font-medium text-gray-500">
                                Domains
                            </dt>
                            <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {domains.length}
                            </dd>
                        </div>
                        <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt className="text-sm font-medium text-gray-500">
                                Categories
                            </dt>
                            <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {domains.reduce((count, domain) => count + domain.categories.length, 0)}
                            </dd>
                        </div>
                        <div className="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt className="text-sm font-medium text-gray-500">
                                Criteria
                            </dt>
                            <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {domains.reduce((count, domain) =>
                                    count + domain.categories.reduce((catCount, cat) =>
                                        catCount + cat.criteria.length, 0), 0)}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </MainLayout>
    );
}
