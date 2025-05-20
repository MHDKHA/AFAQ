// resources/js/Layouts/MainLayout.jsx
import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Toaster } from 'react-hot-toast';

export default function MainLayout({ children, title }) {
    const { auth, flash } = usePage().props;
    const { locale, tools } = usePage().props;

    return (
        <div className="min-h-screen bg-gray-50">
            <Toaster position="top-right" />

            {/* Header */}
            <header className="bg-white shadow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            {/* Logo */}
                            <div className="flex-shrink-0 flex items-center">
                                <Link href="/">
                                    <img className="h-8 w-auto" src="/images/logo.png" alt="Logo" />
                                </Link>
                            </div>

                            {/* Tools Navigation */}
                            <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                {tools.map(tool => (
                                    <Link
                                        key={tool.id}
                                        href={route('tools.show', tool.slug)}
                                        className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
                                    >
                                        {locale === 'ar' ? tool.name_ar : tool.name}
                                    </Link>
                                ))}
                            </div>
                        </div>

                        {/* Authentication Links */}
                        <div className="hidden sm:flex sm:items-center sm:ml-6">
                            {auth.user ? (
                                <div className="relative">
                                    <div className="flex items-center">
                                        <Link
                                            href={route('dashboard')}
                                            className="text-sm text-gray-700 focus:outline-none mr-4"
                                        >
                                            Dashboard
                                        </Link>

                                        <Link
                                            href={route('logout')}
                                            method="post"
                                            as="button"
                                            className="text-sm text-gray-700 focus:outline-none"
                                        >
                                            Logout
                                        </Link>
                                    </div>
                                </div>
                            ) : (
                                <div className="space-x-4">
                                    <Link
                                        href={route('login')}
                                        className="text-sm text-gray-700 focus:outline-none"
                                    >
                                        Login
                                    </Link>

                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150"
                                    >
                                        Register
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </header>

            {/* Page Content */}
            <main className="py-10">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {children}
                </div>
            </main>

            {/* Footer */}
            <footer className="bg-white border-t border-gray-200 py-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center text-gray-500 text-sm">
                        © {new Date().getFullYear()} Your Company. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    );
}
