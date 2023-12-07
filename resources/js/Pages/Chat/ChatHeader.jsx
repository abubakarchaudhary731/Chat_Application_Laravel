import React from 'react';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import CallIcon from '@mui/icons-material/Call';
import DuoIcon from '@mui/icons-material/Duo';


const ChatHeader = ({ receiver }) => {
  return (
    <>
    <div className='sticky left-0 w-full z-20 top-0 flex justify-between bg-gray-50 px-2'>
        <div className='flex gap-4 p-1'>
            <img src="https://picsum.photos/200" alt="not found" className='rounded-full w-10 h-10' />
            <p className='font-bold text-xl my-auto'> {receiver?.name} </p>
        </div>
        <div className='flex-grow'></div>
        <div className='flex gap-4 items-center'>
            <i className='cursor-pointer'> <DuoIcon /> </i>
            <i className='cursor-pointer'> <CallIcon /> </i>
            <i className='cursor-pointer'> <MoreVertIcon /> </i>
        </div>
    </div>
    </>
  )
}

export default ChatHeader