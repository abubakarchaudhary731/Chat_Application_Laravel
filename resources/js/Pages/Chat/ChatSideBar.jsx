import React from 'react';
import SearchIcon from '@mui/icons-material/Search';
import { Link } from '@inertiajs/react';


const ChatSideBar = ({ recentMessages }) => {
  
  return (
    <>
        <div className='flex px-2 bg-gray-50 p-1 relative' >
            <i className="p-2 absolute"> <SearchIcon /> </i>
            <input type="text" name="search" id="search"  placeholder='Search' className='pl-10 border-none outline-none w-full bg-gray-50 focus:ring-0' />
        </div>
        {
          recentMessages.length > 0 ? 
          (recentMessages.map((users, index) => {
            return (
              <Link href={`/chat/${users.user_id}`}  key={index}>
                <div className='flex gap-4 mt-5 px-6 cursor-pointer hover:bg-gray-50'>
                <img src="https://picsum.photos/200" alt="not found" className='rounded-full w-16 h-16' />
                <div className='my-auto'>
                    <p className='text-xl font-bold'> {users.name} </p>
                    <p className='text-xs overflow-hidden'> {users.message.message} </p>
                </div>
              </div>
              </Link>
            )
          })) : 
          "No recent messages"
        }

    </>
  )
}

export default ChatSideBar