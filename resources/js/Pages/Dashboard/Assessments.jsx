import React from 'react';
import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';

const AssessmentListItem = ({ assessment }) => {
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString();
    };

    return (
        <div className="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
            <div className="flex items-start justify-between">
                <div>
                    <h3 className="font-bold text-lg mb-1">{assessment.name}</h3>
                    {assessment.tool && (
                        <p className="text-sm text-gray-500 mb-2">
                            {assessment.tool.name}
                        </p>
                    )}
                </div>
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
          {formatDate(assessment.date)}
        </span>
            </div>

            <div className="mt-4">
                <div className="flex items-center mb-2">
                    <span className="text-sm text-gray-600 mr-2">Completion:</span>
                    <div className="flex-1 bg-gray-200 rounded-full h-2.5">
                        <div
                            className="bg-blue-600 h-2.5 rounded-full"
                            style={{ width: `${(assessment.items_count || 0) / 39 * 100}%` }}
                        ></div>
                    </div>
                    <span className="text-sm text-gray-600 ml-2">
            {Math.round((assessment.items_count || 0) / 39 * 100)}%
          </span>
                </div>

                <div className="flex items-center">
                    <span className="text-sm text-gray-600 mr-2">Available:</span>
                    <div className="flex-1 bg-gray-200 rounded-full h-2.5">
                        <div
                            className="bg-green-500 h-2.5 rounded-full"
                            style={{ width: `${assessment.available_count / (assessment.items_count || 1) * 100}%` }}
                        ></div>
                    </div>
                    <span className="text-sm text-gray-600 ml-2">
            {assessment.available_count} / {assessment.items_count || 0}
          </span>
                </div>
            </div>

            <div className="mt-4 flex justify-end space-x-2">
                <Link
                    href={route('tools.show', assessment.tool?.slug)}
                    className="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Continue
                </Link>

                <Link
                    href={route('assessment-dashboard.show', assessment.id)}
                    className="inline-flex items-center px-3 py-1.5 border border-transparent text-sm rounded-md text-white bg-blue-600 hover:bg-blue-700"
                >
                    View Results
                </Link>
            </div>
        </div>
    );
};

const Assessments = ({ assessments }) => {
    return (
        <MainLayout>
            <Head title="My Assessments" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="mb-6 flex items-center justify-between">
                        <h1 className="text-2xl font-bold">My Assessments</h1>
                        <Link
                            href={route('tools.index')}
                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                        >
                            Start New Assessment
                        </Link>
                    </div>

                    {assessments.length === 0 ? (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-12 bg-white border-b border-gray-200 text-center">
                                <p className="text-gray-500 mb-4">You haven't completed any assessments yet.</p>
                                <Link
                                    href={route('tools.index')}
                                    className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Browse Assessment Tools
                                </Link>
                            </div>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {assessments.map(assessment => (
                                <AssessmentListItem key={assessment.id} assessment={assessment} />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </MainLayout>
    );
};

export default Assessments;
