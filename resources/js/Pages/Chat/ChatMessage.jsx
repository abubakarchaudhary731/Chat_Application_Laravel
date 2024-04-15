import React from 'react';
import SendIcon from '@mui/icons-material/Send';
import TextInput from '@/Components/TextInput';
import { useForm } from '@inertiajs/react';

const ChatMessage = ({ receiver, messages, user }) => {
  const { data, setData, post, reset } = useForm({
    message: '',
  })
  const receiverMessages = (message) => {
    return message.receiver_id === user.id;
  }

  const handleChange = (e) => {
    setData(e.target.name, e.target.value);
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    if (data.message.length > 0) {
      post(route('chat.store', receiver.id), {
        onSuccess: () => {
          reset('message');
        }
      })
    }
  }
  return (
    <>
      {
        receiver?.id ? (
          <>
            <div className='mt-14 px-5 flex justify-start flex-col mb-20'>
              {
                (messages || []).map((message, index) => {
                  const isReceiver = receiverMessages(message);

                  return (
                    <div
                      className={`rounded-lg px-5 
                ${isReceiver ? 'bg-violet-500 text-white' : ' text-black justify-end bg-slate-200'}
                max-w-[75%] my-2 ${isReceiver ? 'self-start' : 'self-end'}`}
                      key={index}
                    >
                      <p className='p-2'>{message.message}</p>
                    </div>
                  );
                })
              }
            </div>
            <div className='flex-grow'></div>

            <div className='bg-gray-50 sticky bottom-16 z-20 w-full rounded-t-lg'>
              <form action="" onSubmit={handleSubmit} className='relative'>
                <TextInput
                  value={data.message} onChange={handleChange}
                  type="text" name='message' id='message'
                  placeholder='Type a message'
                  className='border-none outline-none w-full bg-gray-50 focus:outline-none focus:ring-0 p-5 text-xl' />
                <button type="submit" className='absolute right-4 top-1/2 transform -translate-y-1/2 h-full p-3 text-gray-600'> <SendIcon /> </button>
              </form>
            </div>

          </>
        ) : (
          <div className='h-full flex justify-center'>
            <h1 className='my-auto font-bold text-4xl text-gray-500'> Please Select a User for Chat </h1>
          </div>
        )
      }
    </>
  )
}

export default ChatMessage