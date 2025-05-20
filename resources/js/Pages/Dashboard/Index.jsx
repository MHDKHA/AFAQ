// resources/js/Pages/Dashboard/Index.jsx
import React from 'react';
import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

const Dashboard = () => {
    return (
        <MainLayout>
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h1 className="text-2xl font-bold mb-6">Dashboard</h1>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <Link
                                    href={route('dashboard.assessments')}
                                    className="block p-6 bg-blue-50 hover:bg-blue-100 rounded-lg shadow-sm transition"
                                >
                                    <h2 className="text-xl font-bold mb-2 text-blue-700">My Assessments</h2>
                                    <p className="text-blue-600">View and manage your completed assessments</p>
                                </Link>

                                <Link
                                    href={route('tools.index')}
                                    className="block p-6 bg-green-50 hover:bg-green-100 rounded-lg shadow-sm transition"
                                >
                                    <h2 className="text-xl font-bold mb-2 text-green-700">Assessment Tools</h2>
                                    <p className="text-green-600">Browse available assessment tools</p>
                                </Link>

                                <div className="block p-6 bg-gray-50 rounded-lg shadow-sm">
                                    <h2 className="text-xl font-bold mb-2 text-gray-700">Account Settings</h2>
                                    <p className="text-gray-600">Coming soon...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
};

export default Dashboard;
