// resources/js/Pages/Dashboard.jsx
import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function Dashboard({ assessments, showSubscriptionPrompt, hasSubscription }) {
    const { auth, locale } = usePage().props;

    return (
        <MainLayout title="Dashboard">
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-2xl font-semibold text-gray-900 mb-6">
                        Your Assessments
                    </h1>

                    {showSubscriptionPrompt && !hasSubscription && (
                        <div className="bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-8">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <svg className="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <div className="text-indigo-800">
                                        <h3 className="text-sm font-medium">Get Full Access to Your Assessment Results</h3>
                                        <div className="mt-2 text-sm">
                                            <p>Subscribe now to unlock detailed reports, recommendations, and expert insights.</p>
                                        </div>
                                        <div className="mt-3">
                                            <Link
                                                href={route('subscribe')}
                                                className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                Subscribe Now
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {assessments.length === 0 ? (
                        <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                            <div className="p-6 text-center">
                                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 className="mt-2 text-sm font-medium text-gray-900">No assessments</h3>
                                <p className="mt-1 text-sm text-gray-500">Get started by creating a new assessment.</p>
                                <div className="mt-6">
                                    <Link
                                        href={route('tools.index')}
                                        className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        View Assessment Tools
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {assessments.map(assessment => (
                                <div key={assessment.id} className="bg-white overflow-hidden shadow rounded-lg divide-y divide-gray-200">
                                    <div className="px-4 py-5 sm:px-6">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900">
                                            {locale === 'ar' ? assessment.name_ar : assessment.name}
                                        </h3>
                                        <p className="mt-1 text-sm text-gray-500">
                                            {locale === 'ar' ? assessment.tool.name_ar : assessment.tool.name}
                                        </p>
                                    </div>
                                    <div className="px-4 py-5 sm:p-6">
                                        <div className="space-y-6">
                                            <div>
                                                <div className="flex justify-between">
                                                    <span className="text-sm font-medium text-gray-500">Completion</span>
                                                    <span className="text-sm font-medium text-gray-900">{assessment.completion_percentage}%</span>
                                                </div>
                                                <div className="mt-2 relative pt-1">
                                                    <div className="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                                        <div
                                                            style={{ width: `${assessment.completion_percentage}%` }}
                                                            className="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600"
                                                        ></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div className="flex justify-between">
                                                    <span className="text-sm font-medium text-gray-500">Available Items</span>
                                                    <span className="text-sm font-medium text-gray-900">{assessment.available_count}/{assessment.total_count}</span>
                                                </div>
                                                <div className="mt-2 relative pt-1">
                                                    <div className="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                                        <div
                                                            style={{ width: `${assessment.total_count > 0 ? (assessment.available_count / assessment.total_count) * 100 : 0}%` }}
                                                            className="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"
                                                        ></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="px-4 py-4 sm:px-6">
                                        <div className="flex justify-between space-x-3">
                                            <Link
                                                href={route('assessment.fill', assessment.id)}
                                                className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                Continue Assessment
                                            </Link>

                                            {assessment.has_full_access ? (
                                                <Link
                                                    href={route('filament.afaq.resources.assessments.view', assessment.id)}
                                                    className="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                >
                                                    View Full Report
                                                </Link>
                                            ) : (
                                                <Link
                                                    href={route('subscribe')}
                                                    className="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                >
                                                    Unlock Full Report
                                                </Link>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </MainLayout>
    );
}
