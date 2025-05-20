// resources/js/Pages/Home.jsx
import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

export default function Home() {
    const { tools, locale } = usePage().props;

    return (
        <MainLayout title="Home">
            <div className="py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                            <span className="block">Assessment Tools</span>
                            <span className="block text-indigo-600">For Your Business</span>
                        </h1>
                        <p className="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            Choose from our selection of professional assessment tools to improve your business operations and workplace environment.
                        </p>
                    </div>

                    <div className="mt-16">
                        <h2 className="text-2xl font-bold text-gray-900 mb-8">Available Tools</h2>

                        <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            {tools.map(tool => (
                                <div key={tool.id} className="flex flex-col bg-white overflow-hidden shadow-lg rounded-lg">
                                    <div className="p-6 flex-1">
                                        <h3 className="text-xl font-semibold text-gray-900">
                                            {locale === 'ar' ? tool.name_ar : tool.name}
                                        </h3>
                                        <p className="mt-3 text-base text-gray-500">
                                            {locale === 'ar' ? tool.description_ar : tool.description}
                                        </p>
                                    </div>
                                    <div className="bg-gray-50 px-6 py-4">
                                        <Link
                                            href={route('tools.show', tool.slug)}
                                            className="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                                        >
                                            Start Assessment
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
