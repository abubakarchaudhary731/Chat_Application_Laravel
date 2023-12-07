import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ChatSideBar from './ChatSideBar';
import ChatHeader from './ChatHeader';
import ChatMessage from './ChatMessage';

export default function Home(props) {
    console.log(props);
    return (
        <AuthenticatedLayout
            user={props.auth.user}
            // header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Home</h2>}
        >
        <Head title="Home" />

            <div className="pt-5 pb-2">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg h-[89vh]">
                    <div className='flex justify-between px-5'>
                        <div className='my-auto flex gap-3'>
                            <div className='w-3 h-3 rounded-full bg-red-500'></div>
                            <div className='w-3 h-3 rounded-full bg-yellow-500'></div>
                            <div className='w-3 h-3 rounded-full bg-green-500'></div>
                        </div>
                        <div className="p-5 text-gray-900"> Messenger UI </div>
                    </div>
                        <div className='flex border-t-2 border-gray-300 h-full relative'>
                            <div className='basis-2/6 border-r-2 border-gray-300'>
                               <ChatSideBar recentMessages={props.recentMessages} />
                            </div>
                            <div className='relative basis-4/6 flex flex-col h-full overflow-auto '>
                                <ChatHeader receiver={props.receiver} /> 
                                <ChatMessage receiver={props.receiver} messages={props.messages} user={props.auth.user} />
                            </div>

                        </div>
                    </div>
                    

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
